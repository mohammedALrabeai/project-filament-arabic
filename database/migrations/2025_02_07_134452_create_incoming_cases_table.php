<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incoming_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->comment('رقم القضية');
            $table->year('year')->comment('السنة');
            $table->string('general_number')->unique()->comment('الرقم العام');
            $table->text('subject')->comment('موضوع القضية');
            $table->boolean('is_serious')->default(false)->comment('هل هي جسيمة');
            $table->string('plaintiff')->comment('المستانف');
            $table->string('defendant')->comment('المستانف ضده');
            $table->string('authority')->comment('الجهة');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incoming_cases');
    }
};
