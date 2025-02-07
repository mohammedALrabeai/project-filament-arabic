<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terminated_cases', function (Blueprint $table) {
            $table->id();
            // نستخدم الرقم العام كمرجع من جدول incoming_cases
            $table->string('general_number')->comment('الرقم العام');
            // تعريف المفتاح الخارجي (Foreign Key) على العمود general_number في جدول incoming_cases
            $table->foreign('general_number')
                ->references('general_number')->on('incoming_cases')
                ->onDelete('cascade');

            $table->string('verdict_number')->comment('رقم الحكم');
            // يمكنك تخزين تاريخ صدور الحكم كنص إذا كان بصيغة هجريّة نصيّة، أو كـ date إذا توافرت لديك مكتبة تحويل
            $table->date('verdict_date')->comment('تاريخ صدور الحكم بالهجري');
            $table->string('verdict_method')->comment('كيفية صدور الحكم');
            $table->string('draft_editor')->comment('اسم محرر المسودة');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terminated_cases');
    }
};
