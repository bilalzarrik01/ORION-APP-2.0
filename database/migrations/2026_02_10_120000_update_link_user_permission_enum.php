<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `link_user` MODIFY `permission` ENUM('lecture','edition') NOT NULL DEFAULT 'lecture'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `link_user` MODIFY `permission` ENUM('read','edit') NOT NULL DEFAULT 'read'");
    }
};
