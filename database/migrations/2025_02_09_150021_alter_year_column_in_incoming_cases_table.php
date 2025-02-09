<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterYearColumnInIncomingCasesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incoming_cases', function (Blueprint $table) {
            // تعديل نوع العمود 'year' ليصبح integer بدلاً من year
            $table->integer('year')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_cases', function (Blueprint $table) {
            // في حالة رغبتك في التراجع، يمكنك إعادة العمود إلى النوع السابق
            // لاحظ أنه قد تحتاج إلى تعديل هذا السطر حسب نوع قاعدة البيانات والإعدادات الأصلية
            $table->year('year')->change();
        });
    }
}
