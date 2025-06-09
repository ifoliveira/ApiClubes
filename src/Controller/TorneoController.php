<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EquipoTorneoRepository;
use App\Repository\EquipoGrupoRepository;
use App\Repository\PartidoGrupoRepository;
use App\Repository\TorneosRepository;   
use App\Entity\Grupo;


class TorneoController extends AbstractController
{
    #[Route('/torneo/{slug}/entrada', name: 'torneo_entrada')]
    public function entrada(string $slug, EquipoTorneoRepository $equipoTorneoRepository, TorneosRepository $torneosRepository): Response
    {
        $torneo = $torneosRepository->findOneBy(['slug' => $slug]);

        if (!$torneo) {
            throw $this->createNotFoundException('Torneo no encontrado');
        }
    
        $equipos = $equipoTorneoRepository->findBy([
            'torneo' => $torneo,
        ]);

        return $this->render('torneo/entrada.html.twig', [
            'torneo' => $torneo,
            'equipos' => $equipos,
        ]);
    }

    #[Route('/torneo/equipo/{id}/clasificacion', name: 'clasificacion_grupo')]
    public function clasificacionGrupo(
        int $id,
        EquipoGrupoRepository $equipoGrupoRepository,
        PartidoGrupoRepository $partidoRepo,
        Request $request
    ): Response {
        $equipoGrupo = $equipoGrupoRepository->findOneBy(['equipo' => $id]);
    
        if (!$equipoGrupo) {
            throw $this->createNotFoundException('El equipo no pertenece a ningún grupo');
        }
    
        $grupo = $equipoGrupo->getGrupo();
    
        $partidos = $partidoRepo->findBy(['grupo' => $grupo], ['fecha' => 'ASC']);

        foreach ($partidos as $partido) {
            if ($partido->getEstado() === 'en_juego' && $partido->getFecha() !== null) {
                $now = new \DateTime();
                $inicio = $partido->getFecha();
                $interval = $inicio->diff($now);
                $minuto = ($interval->h * 60) + $interval->i;
        
                // si quieres puedes añadirlo como propiedad virtual
                $partido->minutoEnJuego = $minuto;
            }
        }
                
        $equiposDelGrupo = $equipoGrupoRepository->findBy(['grupo' => $grupo]);

        $clasificacion = $this->calcularClasificacion($grupo, $partidoRepo);
    
        // Luego en Twig puedes calcular la tabla (o hacerlo aquí con lógica si prefieres)
    
        return $this->render('torneo/clasificacion.html.twig', [
            'grupo' => $grupo,
            'equiposGrupo' => $equiposDelGrupo,
            'equipoSeleccionado' => $equipoGrupo->getEquipo(),
            'clasificacion' => $clasificacion,
            'partidos' => $partidos,
        ]);
    }    


    #[Route('/torneo/resultados', name: 'torneo_resultados')]
    public function resultados(Request $request): Response
    {
        $equipoSeleccionado = $request->query->get('equipo');
    
        // Aquí decides a qué grupo pertenece. Lo simulamos como Grupo A
        $grupo = 'Grupo A';
    
        // Simulación de clasificación (ordenada por puntos)
        $clasificacion = [
            ['nombre' => 'Alevín A', 'puntos' => 9, 'gf' => 10, 'gc' => 2, 'desempate' => '+8'],
            ['nombre' => 'Benjamín B', 'puntos' => 6, 'gf' => 7, 'gc' => 3, 'desempate' => '+4'],
            ['nombre' => 'Alevín B', 'puntos' => 4, 'gf' => 5, 'gc' => 6, 'desempate' => '-1'],
            ['nombre' => 'Benjamín A', 'puntos' => 3, 'gf' => 4, 'gc' => 7, 'desempate' => '-3'],
            ['nombre' => 'Infantil A', 'puntos' => 1, 'gf' => 2, 'gc' => 10, 'desempate' => '-8'],
        ];
    
        return $this->render('torneo/resultados.html.twig', [
            'equipoSeleccionado' => $equipoSeleccionado,
            'grupo' => $grupo,
            'clasificacion' => $clasificacion,
        ]);
    }  

    #[Route('/torneo/cuadro-final', name: 'torneo_cuadro_final')]
    public function cuadroFinal(): Response
    {
        // Datos simulados (en práctica se sacan de clasificaciones)
        $semifinales = [
            [
                'partido' => 'Semifinal 1',
                'local' => 'Alevín A',
                'visitante' => 'Benjamín B',
                'goles_local' => 2,
                'goles_visitante' => 1,
            ],
            [
                'partido' => 'Semifinal 2',
                'local' => 'Benjamín A',
                'visitante' => 'Alevín B',
                'goles_local' => 0,
                'goles_visitante' => 3,
            ],
        ];
    
        $final = [
            'partido' => 'Final',
            'local' => 'Alevín A',
            'visitante' => 'Alevín B',
            'goles_local' => 1,
            'goles_visitante' => 2,
        ];
    
        $tercerPuesto = [
            'partido' => '3º y 4º Puesto',
            'local' => 'Benjamín B',
            'visitante' => 'Benjamín A',
            'goles_local' => 2,
            'goles_visitante' => 2,
            'penaltis' => '5-4',
        ];
    
        return $this->render('torneo/cuadro_final.html.twig', [
            'semifinales' => $semifinales,
            'final' => $final,
            'tercerPuesto' => $tercerPuesto,
        ]);
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