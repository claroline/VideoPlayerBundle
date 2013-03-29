<?php

namespace Claroline\VideoPlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//todo use sf2.2 BinaryFileResponse
class VideoPlayerController extends Controller
{
    /**
     * @Route(
     *     "/stream/video/{videoId}",
     *     name="claro_stream_video"
     * )
     */
    public function streamAction($videoId)
    {
        $video = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('ClarolineCoreBundle:Resource\File')
            ->find($videoId);

        $response = new StreamedResponse();
        $path = $this->container->getParameter('claroline.files.directory')
            . DIRECTORY_SEPARATOR
            . $video->getHashName();
        $response->setCallBack(
            function () use ($path) {
                readfile($path);
            }
        );
        $response->headers->set('Content-Type', $video->getMimeType());

        return $response;
    }
}