<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TorneosRepository;
use App\Repository\PartidoGrupoRepository;
use App\Entity\Torneos;
use App\Entity\PartidoGrupo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/admin")
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
        public function seguimientoVivo(string $slug, TorneosRepository $torneoRepo, PartidoGrupoRepository $partidoRepo): Response
        {
            $torneo = $torneoRepo->findOneBy(['slug' => $slug]);

            $partidos = $partidoRepo->createQueryBuilder('p')
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
                'partidos' => $partidos,
            ]);
        }    

        #[Route('/partido/{id}/accion', name: 'partido_accion_rapida', methods: ['POST'])]
        public function accionRapida(Request $request, PartidoGrupoRepository $repo, EntityManagerInterface $em, int $id): Response
        {
            $partido = $repo->find($id);
            if (!$partido) {
                throw $this->createNotFoundException('Partido no encontrado');
            }
        
            $accion = $request->request->get('accion');
        
            switch ($accion) {
                case 'iniciar':
                    $partido->setEstado('En Juego');
                    if ($partido->getFecha() === null) {
                        $partido->setFecha(new \DateTime());
                    }
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
            
                case 'finalizar':
                    $partido->setEstado('Finalizado');
                    break;
            }
        
            $em->persist($partido);
            $em->flush();
        
            return $this->redirectToRoute('seguimiento_vivo', ['slug' => $partido->getGrupo()->getTorneo()->getSlug()]);
        }
        
}
?>