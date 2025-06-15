<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TorneosRepository;
use App\Repository\PartidoGrupoRepository;
use App\Entity\Torneos;
use App\Entity\Grupo;
use App\Entity\PartidoGrupo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Torneo\CuadroFinalService;
use App\Repository\PartidoFinalRepository;
use App\Repository\GrupoRepository;
use App\Entity\EquipoTorneo;
use App\Entity\EquipoGrupo;

/**
 * @Route("/")
 */

class AdminController extends AbstractController
{

    #[Route('/torneo/{slug}/resultados', name: 'resultados_torneo')]
    public function introducirResultados(string $slug, TorneosRepository $torneoRepo, PartidoGrupoRepository $partidoRepo): Response
    {
        $torneo = $torneoRepo->findOneBy(['slug' => $slug]);

        $partidos = $partidoRepo->createQueryBuilder('p')
            ->join('p.grupo', 'g')
            ->where('g.torneo = :torneo')
            ->setParameter('torneo', $torneo)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('torneo/introducir_resultados.html.twig', [
            'torneo' => $torneo,
            'partidos' => $partidos,
        ]);
    }

    #[Route('/admin/torneo/{slug}/resultados/guardar', name: 'guardar_resultados', methods: ['POST'])]
    public function guardarResultados(Request $request, PartidoGrupoRepository $partidoRepo, EntityManagerInterface $em): Response
    {
        $resultados = $request->request->all('resultados');
        $acciones = $request->request->all('acciones');
    
        foreach ($resultados as $partidoId => $resultado) {
            $partido = $partidoRepo->find($partidoId);
            if (!$partido) continue;
    
            $golesLocal = $resultado['local'] !== '' ? (int) $resultado['local'] : null;
            $golesVisitante = $resultado['visitante'] !== '' ? (int) $resultado['visitante'] : null;
    
            $partido->setGolesLocal($golesLocal);
            $partido->setGolesVisitante($golesVisitante);
    
            // Nuevo: gestionar el estado
            if (isset($acciones[$partidoId])) {
                $accion = $acciones[$partidoId];
    
                if ($accion === 'iniciar') {
                    $partido->setEstado('En Juego');
                    if ($partido->getFecha() === null) {
                        $partido->setFecha(new \DateTime()); // Marca hora de inicio si no había
                    }
                } elseif ($accion === 'finalizar') {
                    $partido->setEstado('Finalizado');
                }
            }
    
            $em->persist($partido);
        }
    
        $em->flush();
    
        $this->addFlash('success', 'Resultados y estados actualizados');
        return $this->redirectToRoute('resultados_torneo', ['slug' => $request->attributes->get('slug')]);
    }
    

    #[Route('/torneo/{slug}/seguimiento', name: 'seguimiento_vivo')]
    public function seguimientoVivo(
        string $slug,
        TorneosRepository $torneoRepo,
        PartidoGrupoRepository $partidoRepo,
        PartidoFinalRepository $partidoFinalRepo
    ): Response {
        $torneo = $torneoRepo->findOneBy(['slug' => $slug]);
        if (!$torneo) {
            throw $this->createNotFoundException('Torneo no encontrado');
        }
        
    
        return $this->render('torneo/adminEntrada.html.twig', [
            'torneo' => $torneo,
        ]);
    }
    

    #[Route('/admin/{slug}/resolver-empates', name: 'admin_resolver_empates_global')]
    public function resolverEmpatesGlobal(string $slug, TorneosRepository $torneoRepo): Response
    {
        $torneo = $torneoRepo->findOneBy(['slug' => $slug]);
        if (!$torneo) {
            throw $this->createNotFoundException('Torneo no encontrado');
        }
    
        return $this->render('torneo/resolver_empates_global.html.twig', [
            'torneo' => $torneo,
        ]);
    }

    
    #[Route('/torneo/{slug}/seguimientoGrupos', name: 'seguimiento_vivo_grupos')]
    public function seguimientoVivoGrupos(
        string $slug,
        TorneosRepository $torneoRepo,
        PartidoGrupoRepository $partidoRepo,
        PartidoFinalRepository $partidoFinalRepo
    ): Response {
        $torneo = $torneoRepo->findOneBy(['slug' => $slug]);
    
        // Partidos de grupo
        $partidosGrupo = $partidoRepo->createQueryBuilder('p')
            ->join('p.grupo', 'g')
            ->where('g.torneo = :torneo')
            ->setParameter('torneo', $torneo)
            ->addSelect("
                CASE 
                    WHEN p.estado = 'En Juego' THEN 0
                    WHEN p.estado IS NULL OR p.estado = 'no_iniciado' THEN 1
                    WHEN p.estado = 'finalizado' THEN 2
                    ELSE 3
                END AS HIDDEN ordenEstado
            ")
            ->orderBy('ordenEstado', 'ASC')
            ->addOrderBy('p.fecha', 'ASC')
            ->getQuery()
            ->getResult();
    
    
        return $this->render('torneo/seguimiento.html.twig', [
            'torneo' => $torneo,
            'partidos' => $partidosGrupo,
        ]);
    }

    #[Route('/torneo/{slug}/seguimientoFinal', name: 'seguimiento_vivo_final')]
    public function seguimientoVivoFinal(
        string $slug,
        TorneosRepository $torneoRepo,
        PartidoGrupoRepository $partidoRepo,
        PartidoFinalRepository $partidoFinalRepo
    ): Response {
        $torneo = $torneoRepo->findOneBy(['slug' => $slug]);
    
        // Partidos de fase final
        $partidosFinal = $partidoFinalRepo->createQueryBuilder('pf')
            ->where('pf.torneo = :torneo')
            ->setParameter('torneo', $torneo)
            ->addSelect("
                CASE 
                    WHEN pf.fase = 'final' THEN 0
                    WHEN pf.fase = 'semifinal' THEN 1
                    WHEN pf.fase = '3y4puesto' THEN 2
                    ELSE 3
                END AS HIDDEN ordenEstado
            ")
            ->orderBy('ordenEstado', 'ASC')
            ->addOrderBy('pf.fecha', 'ASC')
            ->getQuery()
            ->getResult();
    
    
        return $this->render('torneo/seguimiento.html.twig', [
            'torneo' => $torneo,
            'partidos' => $partidosFinal,
        ]);
    }
        
    

        #[Route('/partido/{id}/accion', name: 'partido_accion_rapida', methods: ['POST'])]
        public function accionRapida(    
            Request $request,
            PartidoGrupoRepository $grupoRepo,
            PartidoFinalRepository $finalRepo,
            EntityManagerInterface $em,
            cuadroFinalService $cuadroFinalService,
            int $id
        ): Response
        {

            $partido = $grupoRepo->find($id);
            $esGrupo = true;
        
            if (!$partido) {
                $partido = $finalRepo->find($id);
                $esGrupo = false;
            }
        
            if (!$partido) {
                throw $this->createNotFoundException('Partido no encontrado');
            }

            $torneo = $esGrupo
            ? $partido->getGrupo()->getTorneo()
            : $partido->getTorneo();            


        
            $accion = $request->request->get('accion');
        
            switch ($accion) {
                case 'iniciar':
                    // Asegurar que no es alias, sino equipo real
                    if (!$partido->getLocal() || !$partido->getVisitante()) {

                        if ($esGrupo) {
                            $this->addFlash('error', 'Faltan equipos asignados en el partido.');
                            return $this->redirectToRoute('seguimiento_vivo_grupos', ['slug' => $torneo->getSlug()]);
                        } else {

                            $this->addFlash('error', 'Faltan equipos asignados en el partido.');
                      
                            return $this->redirectToRoute('seguimiento_vivo_final', [
                                'slug' => $torneo->getSlug()
                            ]);
                        }

                        $this->addFlash('error', 'Faltan equipos asignados en el partido.');
                        return $this->redirectToRoute('seguimiento_vivo_grupos', ['slug' => $torneo->getSlug()]);
                    }
                
                    $partido->setEstado('En Juego');
                    $partido->setGolesLocal(0);
                    $partido->setGolesVisitante(0);
                    $partido->setFechaInicio(new \DateTime());
                    break;
            
                case 'gol_local':
                    $partido->setGolesLocal(($partido->getGolesLocal() ?? 0) + 1);
                    break;
            
                case 'restar_local':
                    $actualLocal = $partido->getGolesLocal() ?? 0;
                    $partido->setGolesLocal(max(0, $actualLocal - 1));
                    break;
            
                case 'gol_visitante':
                    $partido->setGolesVisitante(($partido->getGolesVisitante() ?? 0) + 1);
                    break;
            
                case 'restar_visitante':
                    $actualVisitante = $partido->getGolesVisitante() ?? 0;
                    $partido->setGolesVisitante(max(0, $actualVisitante - 1));
                    break;

                case 'reiniciar':
                    $partido->setEstado(null);
                    $partido->setFecha(null);
                    $partido->setGolesLocal(null);
                    $partido->setGolesVisitante(null);
                    $partido->setFechaInicio(null);
                    if ($esGrupo === true) {
                    $cuadroFinalService->verificarYActualizarCrucesPorGrupo($partido->getGrupo());
                    } else {
                        $cuadroFinalService->rellenarSiguientesCruces($partido);
                        $partido->setPenalties(null); // Reiniciar penaltis si existían
                    }
                    break;                    
            
                case 'finalizar':
                    $partido->setEstado('Finalizado');
                    $partido->setFechaFin(new \DateTime());
                    if ($esGrupo === true) {
                        $cuadroFinalService->verificarYActualizarCrucesPorGrupo($partido->getGrupo());
                    } else {
                        $cuadroFinalService->rellenarSiguientesCruces($partido);

                    }
                    break;

                case 'penalties':
                    $pen = $request->request->get('penalties');
                      if (!preg_match('/^\d+\-\d+$/', $pen)) {
                          $this->addFlash('error', 'Formato inválido de penaltis. Usa "5-4"');
                      } else {
                        $partido->setPenalties($pen);
                        $cuadroFinalService->rellenarSiguientesCruces($partido);
                        $this->addFlash('success', 'Penaltis guardados correctamente.');
                      }
                        break;                    
            }
        
            $em->persist($partido);
            $em->flush();


        
        if ($esGrupo) {
            return $this->redirectToRoute('seguimiento_vivo_grupos', [
                'slug' => $torneo->getSlug()
            ]);
        } else {
            return $this->redirectToRoute('seguimiento_vivo_final', [
                'slug' => $torneo->getSlug()
            ]);
        }

        }
        
        #[Route('/admin/grupo/{grupoId}/resolver-empates', name: 'resolver_empates')]
        public function resolverEmpates(
            int $grupoId,
            PartidoGrupoRepository $partidoRepo,
            GrupoRepository $grupoRepository,
            Request $request,
            EntityManagerInterface $em,
            cuadroFinalService $cuadroFinalService,
        ): Response {
            $grupo = $grupoRepository->find($grupoId);
        
            if (!$grupo) {
                throw $this->createNotFoundException('Grupo no encontrado');
            }
        
            $equipos = $grupo->getEquipoGrupos()->map(fn($eg) => $eg->getEquipo())->toArray();
        
            // Cálculo de clasificación actual
            $tabla = $this->calcularClasificacion($grupo, $partidoRepo);
        
            // Detectar equipos empatados (misma puntuación, dg y gf)
            $empates = [];
        
            foreach ($tabla as $index => $equipo) {
                $clave = $equipo['puntos'] . '-' . $equipo['dg'] . '-' . $equipo['gf'];
                $empates[$clave][] = $equipo;
            }
        
            $equiposEmpatados = [];
            foreach ($empates as $grupoEmpatado) {
                if (count($grupoEmpatado) > 1) {
                    foreach ($grupoEmpatado as $eq) {
                        $equiposEmpatados[] = $eq['equipo'];
                    }
                }
            }
        
            // Procesar envío del formulario
            if ($request->isMethod('POST')) {
                $posiciones = $request->request->all('posiciones');
                foreach ($posiciones as $equipoId => $pos) {
                    $equipo = $em->getRepository(EquipoTorneo::class)->find($equipoId);
                    if ($equipo) {
                        $eg = $em->getRepository(EquipoGrupo::class)->findOneBy([
                            'grupo' => $grupo,
                            'equipo' => $equipo,
                        ]);
                        if ($eg) {
                            $eg->setPosicionManual((int)$pos);
                            $em->persist($eg);
                        }
                    }
                }
        
                $em->flush();
                $cuadroFinalService->verificarYActualizarCrucesPorGrupo($grupo);
                $this->addFlash('success', 'Posiciones manuales actualizadas correctamente');
                return $this->render('torneo/resolver_empates_global.html.twig', [
                    'torneo' => $grupo->getTorneo(),
                ]);
            }
        


            return $this->render('torneo/resolver_empates.html.twig', [
                'grupo' => $grupo,
                'equiposEmpatados' => $equiposEmpatados
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
}
?>