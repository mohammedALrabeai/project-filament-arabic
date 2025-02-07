<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TerminatedCaseResource\Pages;
use App\Models\IncomingCase;
use App\Models\TerminatedCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TerminatedCaseResource extends Resource
{
    protected static ?string $model = TerminatedCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'القضايا المنتهية';

    protected static ?string $pluralLabel = 'القضايا المنتهية';

    protected static ?string $modelLabel = 'قضية منتهية';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // حقل اختيار الرقم العام من القضايا الواردة
                Forms\Components\Select::make('general_number')
                    ->label('الرقم العام')
                    ->options(
                        IncomingCase::query()->pluck('general_number', 'general_number')->toArray()
                    )
                    ->reactive() // لجعل الحقل تفاعلياً عند تغييره
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            // البحث عن القضية الواردة بناءً على الرقم العام
                            $record = IncomingCase::where('general_number', $state)->first();
                            if ($record) {
                                // تحديث الحقول الموجودة في القسم الخاص ببيانات القضية الواردة
                                $set('incomingCase.case_number', $record->case_number);
                                $set('incomingCase.year', $record->year);
                                $set('incomingCase.subject', $record->subject);
                                $set('incomingCase.is_serious', $record->is_serious);
                                $set('incomingCase.plaintiff', $record->plaintiff);
                                $set('incomingCase.defendant', $record->defendant);
                                $set('incomingCase.authority', $record->authority);
                            }
                        }
                    })
                    ->required(),

                // قسم عرض بيانات القضية الواردة (يمكن إخفاؤه وإظهاره)
                Forms\Components\Section::make('بيانات القضية الواردة')
                    ->schema([
                        Forms\Components\TextInput::make('incomingCase.case_number')
                            ->label('رقم القضية')
                            ->disabled(),
                        Forms\Components\TextInput::make('incomingCase.year')
                            ->label('السنة')
                            ->disabled(),
                        Forms\Components\Textarea::make('incomingCase.subject')
                            ->label('موضوع القضية')
                            ->disabled(),
                        Forms\Components\Toggle::make('incomingCase.is_serious')
                            ->label('هل هي جسيمة')
                            ->disabled(),
                        Forms\Components\TextInput::make('incomingCase.plaintiff')
                            ->label('المستانف')
                            ->disabled(),
                        Forms\Components\TextInput::make('incomingCase.defendant')
                            ->label('المستانف ضده')
                            ->disabled(),
                        Forms\Components\TextInput::make('incomingCase.authority')
                            ->label('الجهة')
                            ->disabled(),
                    ])
                    ->collapsible() // يجعل القسم قابل للإخفاء/الإظهار
                    ->collapsed(true), // القسم مخفي بشكل افتراضي

                // باقي الحقول الخاصة بالقضية المنتهية
                Forms\Components\TextInput::make('verdict_number')
                    ->label('رقم الحكم')
                    ->required(),

                // استخدام DatePicker لاختيار تاريخ صدور الحكم
                Forms\Components\DatePicker::make('verdict_date')
                    ->label('تاريخ صدور الحكم بالهجري')
                    ->required(),

                Forms\Components\TextInput::make('verdict_method')
                    ->label('كيفية صدور الحكم')
                    ->required(),

                Forms\Components\TextInput::make('draft_editor')
                    ->label('اسم محرر المسودة')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // بيانات القضية المنتهية
                Tables\Columns\TextColumn::make('general_number')
                    ->label('الرقم العام')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('verdict_number')
                    ->label('رقم الحكم')
                    ->sortable()
                    ->searchable(),

                // استخدام TextColumn لعرض تاريخ صدور الحكم بتنسيق مناسب
                Tables\Columns\TextColumn::make('verdict_date')
                    ->label('تاريخ صدور الحكم بالهجري')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('verdict_method')
                    ->label('كيفية صدور الحكم')
                    ->sortable(),

                Tables\Columns\TextColumn::make('draft_editor')
                    ->label('اسم محرر المسودة')
                    ->sortable(),

                // عرض بيانات القضية الواردة من العلاقة
                Tables\Columns\TextColumn::make('incomingCase.case_number')
                    ->label('رقم القضية')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('incomingCase.year')
                    ->label('السنة')
                    ->sortable(),

                Tables\Columns\TextColumn::make('incomingCase.subject')
                    ->label('موضوع القضية')
                    ->limit(50)
                    ->searchable(),
            ])
            ->filters([
                // فلتر لاختيار الرقم العام من القضايا الواردة
                Tables\Filters\SelectFilter::make('general_number')
                    ->label('الرقم العام')
                    ->options(fn () => IncomingCase::query()
                        ->pluck('general_number', 'general_number')
                        ->toArray()),

                // فلتر اختيار كيفية صدور الحكم
                Tables\Filters\SelectFilter::make('verdict_method')
                    ->label('كيفية صدور الحكم')
                    ->options(fn () => TerminatedCase::query()
                        ->distinct()
                        ->pluck('verdict_method', 'verdict_method')
                        ->toArray()),

                // فلتر نطاق لتاريخ صدور الحكم باستخدام DatePicker
                Tables\Filters\Filter::make('verdict_date')
                    ->label('تاريخ صدور الحكم')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('من'),
                        Forms\Components\DatePicker::make('until')
                            ->label('إلى'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn ($query, $date) => $query->whereDate('verdict_date', '>=', $date)
                            )
                            ->when(
                                $data['until'],
                                fn ($query, $date) => $query->whereDate('verdict_date', '<=', $date)
                            );
                    }),

                // فلتر يعتمد على بيانات القضية الواردة (مثال: رقم القضية وموضوعها)
                Tables\Filters\Filter::make('incoming_case')
                    ->label('بيانات القضية الواردة')
                    ->form([
                        Forms\Components\TextInput::make('case_number')
                            ->label('رقم القضية'),
                        Forms\Components\TextInput::make('subject')
                            ->label('موضوع القضية'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['case_number'],
                                fn ($query, $val) => $query->whereHas('incomingCase', fn ($q) => $q->where('case_number', 'like', "%{$val}%"))
                            )
                            ->when(
                                $data['subject'],
                                fn ($query, $val) => $query->whereHas('incomingCase', fn ($q) => $q->where('subject', 'like', "%{$val}%"))
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
            'index' => Pages\ListTerminatedCases::route('/'),
            'create' => Pages\CreateTerminatedCase::route('/create'),
            'edit' => Pages\EditTerminatedCase::route('/{record}/edit'),
        ];
    }
}
