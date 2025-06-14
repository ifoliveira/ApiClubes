<?php

namespace App\Service\Torneo;

use App\Entity\Grupo;
use App\Entity\PartidoFinal;
use App\Repository\PartidoGrupoRepository;
use App\Repository\EquipoGrupoRepository;
use App\Repository\PartidoFinalRepository;
use Doctrine\ORM\EntityManagerInterface;

class CuadroFinalService
{
    public function __construct(
        private PartidoGrupoRepository $partidoGrupoRepo,
        private EquipoGrupoRepository $equipoGrupoRepo,
        private PartidoFinalRepository $partidoFinalRepo,
        private EntityManagerInterface $em
    ) {}

    public function verificarYActualizarCrucesPorGrupo(Grupo $grupo): void
    {
        $partidos = $this->partidoGrupoRepo->findBy(['grupo' => $grupo]);

        // Verifica si todos los partidos del grupo están finalizados
        foreach ($partidos as $p) {
            if ($p->getEstado() !== 'Finalizado') {
                return; // Aún no ha terminado el grupo
            }
        }

        // Obtener clasificación del grupo (por puntos, etc.)
        $clasificacion = $this->calcularClasificacion($grupo, $this->partidoGrupoRepo);

        if (count($clasificacion) < 2) {
            return; // No hay suficientes equipos
        }

        // A1 y A2
        $grupoLetra = strtoupper(substr($grupo->getNombre(), -1));
        $primero = $clasificacion[0]['equipo']; // EquipoTorneo
        $segundo = $clasificacion[1]['equipo'];

        // Buscar y actualizar partidos con alias A1 o A2
        $this->actualizarAlias($grupoLetra . '1', $primero);
        $this->actualizarAlias($grupoLetra . '2', $segundo);

        $this->em->flush();
    }


    private function actualizarAlias(string $alias, $equipo): void
    {
        $partidos = $this->partidoFinalRepo->findBy([
            'aliasLocal' => $alias
        ]);

        foreach ($partidos as $partido) {
            $partido->setLocal($equipo);
            $partido->setAliasLocal(null);
        }

        $partidos = $this->partidoFinalRepo->findBy([
            'aliasVisitante' => $alias
        ]);

        foreach ($partidos as $partido) {
            $partido->setVisitante($equipo);
            $partido->setAliasVisitante(null);
        }
    }

    private function calcularClasificacion(Grupo $grupo, PartidoGrupoRepository $partidoRepo): array
    {
        $partidos = $partidoRepo->findBy(['grupo' => $grupo]);
    
        $tabla = [];
    
        foreach ($grupo->getEquipoGrupos() as $eg) {
            $equipo = $eg->getEquipo();
            $tabla[$equipo->getId()] = [
                'equipo' => $equipo,
                'puntos' => 0,
                'gf' => 0,
                'gc' => 0,
                'dg' => 0,
                'pj' => 0,
            ];
        }
    
        foreach ($partidos as $partido) {
            $local = $partido->getLocal();
            $visitante = $partido->getVisitante();
            $golesLocal = $partido->getGolesLocal();
            $golesVisitante = $partido->getGolesVisitante();
    
            if (!isset($tabla[$local->getId()]) || !isset($tabla[$visitante->getId()])) {
                continue;
            }
    
            if ($golesLocal !== null && $golesVisitante !== null) {
                $tabla[$local->getId()]['pj']++;
                $tabla[$visitante->getId()]['pj']++;
            
                $tabla[$local->getId()]['gf'] += $golesLocal;
                $tabla[$local->getId()]['gc'] += $golesVisitante;
                $tabla[$visitante->getId()]['gf'] += $golesVisitante;
                $tabla[$visitante->getId()]['gc'] += $golesLocal;
        
                if ($golesLocal > $golesVisitante) {
                    $tabla[$local->getId()]['puntos'] += 3;
                } elseif ($golesLocal < $golesVisitante) {
                    $tabla[$visitante->getId()]['puntos'] += 3;
                } else {
                    $tabla[$local->getId()]['puntos'] += 1;
                    $tabla[$visitante->getId()]['puntos'] += 1;
                }
            } else {
                // Si el partido no tiene goles, no se cuenta
                continue;
            }
        }
    
        foreach ($tabla as &$datos) {
            $datos['dg'] = $datos['gf'] - $datos['gc'];
        }
    
        // Ordenar por puntos, luego diferencia de goles, luego goles a favor
        usort($tabla, function ($a, $b) {
            return [$b['puntos'], $b['dg'], $b['gf']] <=> [$a['puntos'], $a['dg'], $a['gf']];
        });
    
        return $tabla;
    }    
}
?>