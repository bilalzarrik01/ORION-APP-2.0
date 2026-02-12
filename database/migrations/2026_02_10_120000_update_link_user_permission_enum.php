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
        $driver = DB::getDriverName();

        // MySQL: convert legacy enum values (read/edit) to (lecture/edition).
        // SQLite: enum is a CHECK constraint; altering it is non-trivial and this migration is
        // unnecessary on fresh installs because the create migration already uses lecture/edition.
        if ($driver === 'mysql') {
            // 1) Allow both legacy + new values.
            DB::statement("ALTER TABLE `link_user` MODIFY `permission` ENUM('read','edit','lecture','edition') NOT NULL DEFAULT 'lecture'");
            // 2) Migrate existing rows.
            DB::statement("UPDATE `link_user` SET `permission` = 'lecture' WHERE `permission` = 'read'");
            DB::statement("UPDATE `link_user` SET `permission` = 'edition' WHERE `permission` = 'edit'");
            // 3) Restrict to the new vocabulary.
            DB::statement("ALTER TABLE `link_user` MODIFY `permission` ENUM('lecture','edition') NOT NULL DEFAULT 'lecture'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // 1) Allow both legacy + new values.
            DB::statement("ALTER TABLE `link_user` MODIFY `permission` ENUM('read','edit','lecture','edition') NOT NULL DEFAULT 'read'");
            // 2) Migrate rows back.
            DB::statement("UPDATE `link_user` SET `permission` = 'read' WHERE `permission` = 'lecture'");
            DB::statement("UPDATE `link_user` SET `permission` = 'edit' WHERE `permission` = 'edition'");
            // 3) Restrict to the legacy vocabulary.
            DB::statement("ALTER TABLE `link_user` MODIFY `permission` ENUM('read','edit') NOT NULL DEFAULT 'read'");
        }
    }
};
