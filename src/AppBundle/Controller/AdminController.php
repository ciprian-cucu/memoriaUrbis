<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\StoryForm;
use AppBundle\Form\PhotoForm;

use AppBundle\Entity\Story;
use AppBundle\Entity\Photo;

class AdminController extends DefaultController
{

    protected function resize_image($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }

        if (exif_imagetype($file) != IMAGETYPE_PNG) {
            $src = imagecreatefromjpeg($file);
        } else {
            $src = imagecreatefrompng($file);
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        if (exif_imagetype($file) != IMAGETYPE_PNG) {
            imagejpeg($dst, $file);
        } else {
            imagepng($dst, $file);
        }


        // Free up memory
        imagedestroy($dst);
        imagedestroy($src);

        //return $dst;
    }

    protected function uploadPhoto($photo)
    {
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $photo->getFile();

        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $upDir = 'bundles/app/images/stories/';
        $thumbDir = 'bundles/app/images/stories/thumbnails/';

        // Move the file to the directory where picz are stored
        $file->move($upDir,$fileName);
        copy($upDir.$fileName, $thumbDir.$fileName);
        AdminController::resize_image($thumbDir.$fileName,300,300);

        return $fileName;
    }


    public function adminAction(Request $request)
    {
        $fullArray = DefaultController::getStories($request);
        $filter = $fullArray['filter'];
        $stories = $fullArray['stories'];
        $y0 = $fullArray['y0'];
        $y1 = $fullArray['y1'];

        return $this->render('admin/dash.html.twig', array(
            'stories' => $stories  )
        );
    }

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $story = new Story();
        $form = $this->createForm(StoryForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $story = $form->getData();

            //update story order in table
            $stories = $em->getRepository(Story::class)->findLarger($story->getStoryOrder());
            foreach ($stories as $s) {
                $s->setStoryOrder($s->getStoryOrder()+1);
            }
            $em->flush();

            foreach ($story->getPhotos() as $photo) {
                $fileName = AdminController::uploadPhoto($photo);
                $photo->setFile($fileName);
                $photo->setStory($story);
            }

            $em->persist($story);
            $em->flush();

            $this->addFlash("success", "Povestea a fost adaugata!");
            return $this->redirect($request->getUri());
        }
        return $this->render('admin/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    public function photoaddAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $photo = new Photo();
        $form = $this->createForm(PhotoForm::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->getData();
            $fileName = AdminController::uploadPhoto($photo);
            $photo->setFile($fileName);

            //$em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();

            $this->addFlash("success", "Imaginea a fost adaugata!");
            return $this->redirect($request->getUri());

        }

        return $this->render('admin/newphoto.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
