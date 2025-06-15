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
                // Si estaba ya clasificado, revertir
                $grupoLetra = strtoupper(substr($grupo->getNombre(), -1));
                $this->deshacerAlias($grupoLetra . '1');
                $this->deshacerAlias($grupoLetra . '2');
                $this->em->flush();
                return;
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

        }
    
        $partidos = $this->partidoFinalRepo->findBy([
            'aliasVisitante' => $alias
        ]);
    
        foreach ($partidos as $partido) {
            
                $partido->setVisitante($equipo);

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
                'posicionManual' => $eg->getPosicionManual(), 
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
            // Criterios normales
            $criteriosA = [$a['puntos'], $a['dg'], $a['gf']];
            $criteriosB = [$b['puntos'], $b['dg'], $b['gf']];
        
            if ($criteriosA !== $criteriosB) {
                return $criteriosB <=> $criteriosA; // Orden normal
            }
        
            // Si están empatados, miramos si hay posición manual
            $manualA = $a['posicionManual'];
            $manualB = $b['posicionManual'];
        
            if ($manualA !== null && $manualB !== null) {
                return $manualA <=> $manualB; // El más bajo gana
            }
        
            // Si solo uno tiene valor manual, gana ese
            if ($manualA !== null) return -1;
            if ($manualB !== null) return 1;
        
            return 0; // Completamente iguales
        });
        
    
        return $tabla;
    }  

    private function deshacerAlias(string $alias): void
    {
        // Deshacer como local
        $partidosLocal = $this->partidoFinalRepo->findBy(['aliasLocal' => $alias]);
        foreach ($partidosLocal as $partido) {
            if ($partido->getLocal() !== null) {
                $partido->setLocal(null);
            }
        }
    
        // Deshacer como visitante
        $partidosVisitante = $this->partidoFinalRepo->findBy(['aliasVisitante' => $alias]);
        foreach ($partidosVisitante as $partido) {
            if ($partido->getVisitante() !== null) {
                $partido->setVisitante(null);
            }
        }
    }

    public function rellenarSiguientesCruces(PartidoFinal $partido): void
    {
        // Solo si hay resultado válido
        if (
            $partido->getGolesLocal() === null || 
            $partido->getGolesVisitante() === null
        ) {
           // Si estaba ya clasificado, revertir
           $this->deshacerAlias('WINNER-' . $partido->getId());  
           $this->deshacerAlias('LOSER-' . $partido->getId());

          $this->em->flush();
             return;

            }
    
    
        // Determinar ganador y perdedor
        $golesLocal = $partido->getGolesLocal();
        $golesVisitante = $partido->getGolesVisitante();
        $ganador = null;
        $perdedor = null;
    
        if ($golesLocal > $golesVisitante) {
            $ganador = $partido->getLocal();
            $perdedor = $partido->getVisitante();
        } elseif ($golesVisitante > $golesLocal) {
            $ganador = $partido->getVisitante();
            $perdedor = $partido->getLocal();
        } elseif ($partido->getPenalties()) {
            // En caso de empate, usar penaltis
            [$penLocal, $penVisitante] = explode('-', $partido->getPenalties());
            if ((int)$penLocal > (int)$penVisitante) {
                $ganador = $partido->getLocal();
                $perdedor = $partido->getVisitante();
            } else {
                $ganador = $partido->getVisitante();
                $perdedor = $partido->getLocal();
            }
        } else {
            // No puede continuar sin resultado válido
            return;
        }
    
        $id = $partido->getId();
        $this->asignarEquipoPorAlias("WINNER-$id", $ganador);
        $this->asignarEquipoPorAlias("LOSER-$id", $perdedor);
    
        $this->em->flush();
    }
    private function asignarEquipoPorAlias(string $alias, $equipo): void
    {
        // Buscar partidos con el alias
        $partidos = $this->partidoFinalRepo->findBy(['aliasLocal' => $alias]);
    
        foreach ($partidos as $partido) {
            if ($partido->getLocal() === null) {
                $partido->setLocal($equipo);
            }
        }
    
        $partidos = $this->partidoFinalRepo->findBy(['aliasVisitante' => $alias]);
    
        foreach ($partidos as $partido) {
            if ($partido->getVisitante() === null) {
                $partido->setVisitante($equipo);
            }
        }
    }    
    

    
}
?>