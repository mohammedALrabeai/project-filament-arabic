<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingCaseResource\Pages;
use App\Models\IncomingCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncomingCaseResource extends Resource
{
    protected static ?string $model = IncomingCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationLabel = 'القضايا الواردة';

    protected static ?string $pluralLabel = 'القضايا الواردة';

    protected static ?string $modelLabel = 'قضية واردة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ترتيب الحقول عند الإدخال مع القيود المناسبة
                Forms\Components\TextInput::make('general_number')
                    ->label('الرقم العام')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('case_number')
                    ->label('رقم القضية')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('year')
                    ->label('السنة')
                    ->numeric()
                    ->required()
                    ->minValue(1901)
                    ->maxValue(2155)
                    ->helperText('يرجى إدخال سنة صحيحة بين 1901 و2155'),

                Forms\Components\Textarea::make('subject')
                    ->label('موضوع القضية')
                    ->required()
                    ->maxLength(1000),

                Forms\Components\TextInput::make('plaintiff')
                    ->label('المستانف')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('defendant')
                    ->label('المستانف ضده')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('authority')
                    ->label('الجهة')
                    ->required()
                    ->maxLength(255),

                // جعل خيار "هل هي جسيمة" يظهر كخيارات نعم/لا وكآخر حقل
                Forms\Components\Radio::make('is_serious')
                    ->label('هل هي جسيمة')
                    ->options([
                        1 => 'نعم',
                        0 => 'لا',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // عرض الأعمدة مع إمكانية الفرز والبحث
                Tables\Columns\TextColumn::make('general_number')
                    ->label('الرقم العام')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('case_number')
                    ->label('رقم القضية')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('year')
                    ->label('السنة')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('موضوع القضية')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('is_serious')
                    ->label('هل هي جسيمة')
                    ->formatStateUsing(fn ($state) => $state ? 'نعم' : 'لا')
                    ->sortable(),

                Tables\Columns\TextColumn::make('plaintiff')
                    ->label('المستانف')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('defendant')
                    ->label('المستانف ضده')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('authority')
                    ->label('الجهة')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                // فلتر حسب السنة
                Tables\Filters\SelectFilter::make('year')
                    ->label('السنة')
                    ->options(function () {
                        return IncomingCase::query()
                            ->select('year')
                            ->distinct()
                            ->pluck('year', 'year')
                            ->toArray();
                    }),
                // فلتر لحالة "هل هي جسيمة"
                Tables\Filters\TernaryFilter::make('is_serious')
                    ->label('هل هي جسيمة')
                    ->trueLabel('نعم')
                    ->falseLabel('لا'),
                // فلتر بحث في موضوع القضية
                Tables\Filters\Filter::make('subject')
                    ->label('موضوع القضية')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('موضوع القضية'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['subject'],
                            fn ($query, $subject) => $query->where('subject', 'like', "%{$subject}%")
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncomingCases::route('/'),
            'create' => Pages\CreateIncomingCase::route('/create'),
            'edit' => Pages\EditIncomingCase::route('/{record}/edit'),
        ];
    }
}
