<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdToTasksTable extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Ajouter la colonne project_id avec une clé étrangère
            $table->foreignId('project_id')->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Supprimer la colonne project_id
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
}
