<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Renombrar min_points a starting_points
            $table->renameColumn('min_points', 'starting_points');

            // Renombrar max_points a finishing_points
            $table->renameColumn('max_points', 'finishing_points');

            // Agregar is_winning boolean (default true = ganar al llegar)
            $table->boolean('is_winning')->default(true)->after('finishing_points');

            // Eliminar la columna ending ya que is_winning la reemplaza
            $table->dropColumn('ending');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Revertir cambios
            $table->renameColumn('starting_points', 'min_points');
            $table->renameColumn('finishing_points', 'max_points');
            $table->dropColumn('is_winning');

            // Re-agregar ending
            $table->string('ending')->after('rounds');
        });
    }
};
