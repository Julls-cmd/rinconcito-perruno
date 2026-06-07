<?php

namespace Tests\Unit;

use App\Models\Bono;
use Tests\TestCase;

class BonoTest extends TestCase
{
    public function test_bono_vigente_activo_con_usos_y_no_expirado(): void
    {
        $bono = Bono::make([
            'activo'           => true,
            'usos_restantes'   => 5,
            'fecha_expiracion' => now()->addDays(30),
        ]);

        $this->assertTrue($bono->estaVigente());
    }

    public function test_bono_no_vigente_si_inactivo(): void
    {
        $bono = Bono::make([
            'activo'           => false,
            'usos_restantes'   => 5,
            'fecha_expiracion' => now()->addDays(30),
        ]);

        $this->assertFalse($bono->estaVigente());
    }

    public function test_bono_no_vigente_si_sin_usos(): void
    {
        $bono = Bono::make([
            'activo'           => true,
            'usos_restantes'   => 0,
            'fecha_expiracion' => now()->addDays(30),
        ]);

        $this->assertFalse($bono->estaVigente());
    }

    public function test_bono_no_vigente_si_expirado(): void
    {
        $bono = Bono::make([
            'activo'           => true,
            'usos_restantes'   => 5,
            'fecha_expiracion' => now()->subDay(),
        ]);

        $this->assertFalse($bono->estaVigente());
    }
}
