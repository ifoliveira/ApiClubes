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
use App\Repository\GrupoRepository;
use App\Repository\PartidoFinalRepository;
use App\Entity\PartidoFinal;
use Knp\Snappy\Pdf;


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
            'equiposTorneo' => $equipos,
        ]);
    }

    #[Route('/torneo/{slug}/grupo/{grupoId}/equipo/{equipoId}', name: 'clasificacion_grupo')]
    public function clasificacionGrupo(
        ?int $equipoId,
        EquipoGrupoRepository $equipoGrupoRepository,
        PartidoGrupoRepository $partidoRepo,
        GrupoRepository $grupoRepository,
        TorneosRepository $torneosRepository,
        string $slug,
        Request $request
    ): Response {
        $torneo = $torneosRepository->findOneBy(['slug' => $slug]);
        $grupo = $grupoRepository->find($request->attributes->get('grupoId'));
    
        if (!$grupo) {
            throw $this->createNotFoundException('Grupo no encontrado');
        }
    
        $equipoSeleccionado = null;
    
        if ($equipoId) {
            $equipoGrupo = $equipoGrupoRepository->findOneBy(['equipo' => $equipoId, 'grupo' => $grupo]);
    
            if (!$equipoGrupo) {
                throw $this->createNotFoundException('El equipo no pertenece a este grupo');
            }
    
            $equipoSeleccionado = $equipoGrupo->getEquipo();
        }
    
        $partidos = $partidoRepo->findBy(['grupo' => $grupo], ['fecha' => 'ASC']);
    
        foreach ($partidos as $partido) {
            if ($partido->getEstado() === 'en_juego' && $partido->getFecha() !== null) {
                $now = new \DateTime();
                $inicio = $partido->getFecha();
                $interval = $inicio->diff($now);
                $minuto = ($interval->h * 60) + $interval->i;
                $partido->minutoEnJuego = $minuto;
            }
        }
    
        $equiposDelGrupo = $equipoGrupoRepository->findBy(['grupo' => $grupo]);
    
        $clasificacion = $this->calcularClasificacion($grupo, $partidoRepo);
    
        return $this->render('torneo/clasificacion.html.twig', [
            'grupo' => $grupo,
            'equiposGrupo' => $equiposDelGrupo,
            'equipoSeleccionado' => $equipoSeleccionado,
            'clasificacion' => $clasificacion,
            'partidos' => $partidos,
            'grupos' => $grupoRepository->findBy(['torneo' => $torneo]),
            'torneo' => $torneo,
        ]);
    }
    


    #[Route('/torneo/{slug}/cuadro-final', name: 'torneo_cuadro_final')]
    public function cuadroFinal(
        string $slug,
        TorneosRepository $torneosRepository,
        GrupoRepository $grupoRepository,
        PartidoFinalRepository $partidoFinalRepository,
        Request $request
    ): Response 
    {
        $torneo = $torneosRepository->findOneBy(['slug' => $slug]);

        if (!$torneo) {
            throw $this->createNotFoundException('Torneo no encontrado');
        }

        $partidos = $partidoFinalRepository->findBy(
            ['torneo' => $torneo],
            ['fase' => 'ASC'] // opcional si quieres ordenado por fase
        );
        
        // o agrupar por fase en PHP si lo prefieres
        $agrupadosPorFase = [];

        foreach ($partidos as $p) {
            $fase = $p->getFase() ?? 'sin_fase';
        
            if (!array_key_exists($fase, $agrupadosPorFase)) {
                $agrupadosPorFase[$fase] = [];
            }
        
            $agrupadosPorFase[$fase][] = $p;
        }
        
        

        // Renderizar la vista del cuadro final
        return $this->render('torneo/cuadro_final.html.twig', [
            'fases' => $agrupadosPorFase
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
    
    #[Route('/torneo/{slug}/cuadro-final/pdf', name: 'torneo_cuadro_final_pdf')]
    public function cuadroFinalPdf(
        string $slug,
        TorneosRepository $torneosRepository,
        PartidoFinalRepository $partidoFinalRepository,
        Pdf $knpSnappy
    ): Response {
        $torneo = $torneosRepository->findOneBy(['slug' => $slug]);
        $partidos = $partidoFinalRepository->findBy(['torneo' => $torneo]);
    
        // Agrupar dinámicamente por fase
        $fases = [];
        foreach ($partidos as $p) {
            $fase = $p->getFase() ?? 'sin_fase';
            $fases[$fase][] = $p;
        }
    
        $html = $this->renderView('torneo/cuadro_final_pdf.html.twig', [
            'torneo' => $torneo,
            'fases' => $fases,
        ]);
    
        return new Response(
            $knpSnappy->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="cuadro_final_'.$slug.'.pdf"',
            ]
        );
    }
    
    
}
?>