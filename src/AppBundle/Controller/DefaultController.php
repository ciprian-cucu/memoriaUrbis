<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

/* first two functions implement ranked search in PHP */
    public function get_corpus_index($corpus = array(), $separator=' ') {

        $dictionary = array();
        $doc_count = array();

        foreach($corpus as $doc_id => $doc) {
            $terms = explode($separator, $doc);
            $doc_count[$doc_id] = count($terms);

            // tf–idf, short for term frequency–inverse document frequency,
            // according to wikipedia is a numerical statistic that is intended to reflect
            // how important a word is to a document in a corpus

            foreach($terms as $term) {
                if(!isset($dictionary[$term])) {
                    $dictionary[$term] = array('document_frequency' => 0, 'postings' => array());
                }
                if(!isset($dictionary[$term]['postings'][$doc_id])) {
                    $dictionary[$term]['document_frequency']++;
                    $dictionary[$term]['postings'][$doc_id] = array('term_frequency' => 0);
                }

                $dictionary[$term]['postings'][$doc_id]['term_frequency']++;
            }
            //from http://phpir.com/simple-search-the-vector-space-model/
        }

        return array('doc_count' => $doc_count, 'dictionary' => $dictionary);
    }

    public function get_similar_documents($query='', $corpus=array(), $separator=' '){

        $similar_documents=array();
        if($query!=''&&!empty($corpus)){
            $words=explode($separator,$query);
            $corpus=DefaultController::get_corpus_index($corpus);
            $doc_count=count($corpus['doc_count']);

            foreach($words as $word) {
                if (isset($corpus['dictionary'][$word]) || array_key_exists($word, $corpus['dictionary'])) {


                    $entry = $corpus['dictionary'][$word];


                    foreach($entry['postings'] as $doc_id => $posting) {

                        //get term frequency–inverse document frequency
                        $score=$posting['term_frequency'] * log($doc_count + 1 / $entry['document_frequency'] + 1, 2);

                        if(isset($similar_documents[$doc_id])){
                            $similar_documents[$doc_id]+=$score;
                        }
                        else{
                            $similar_documents[$doc_id]=$score;
                        }

                    }
                }

            }
            // length normalise
            foreach($similar_documents as $doc_id => $score) {
                $similar_documents[$doc_id] = $score/$corpus['doc_count'][$doc_id];
            }
            // sort fro  high to low
            arsort($similar_documents);
        }
        return $similar_documents;
    }
/* end php search functions */


    public function getYears()
    //this function gets unique year values, to populate drop down from menu (for the filter)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Story')->createQueryBuilder('s')
          ->select('s.year0')->distinct()
          ->orderBy('s.year0', 'ASC')
          ->getQuery();
        $years = $query->getResult();
        return $years;
    }

    public function getTranslation($lang, $table, $entry, $field) {
    //this function retrieves a string from the translation table, given parameters that define what the text represents
    $em = $this->getDoctrine()->getManager();
    $query = $em->getRepository('AppBundle:Translation')->createQueryBuilder('t')
      ->select('t.translated')
      ->where('t.lang = :lang')
      ->andWhere('t.table = :table')
      ->andWhere('t.entry = :entry')
      ->andWhere('t.field = :field')
      ->setParameter('lang', $lang)
      ->setParameter ('table',$table)
      ->setParameter('entry', $entry)
      ->setParameter ('field',$field)
      ->getQuery();
    $translation = $query->getResult();
    return $translation[0]['translated'];
    }

    public function getStories(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = $search = $kw = $y0 = $y1 = 0;

        // either filtered stories, searched stories or all stories
        if (null != $request->query->get('y0')) {
            $filter = 1;
            $y0 = $request->query->get('y0');
            if (null != $request->query->get('y1')) $y1 = $request->query->get('y1');
            //if second year is "any"
            if ($y1 == 0) {$y1 = 9999;}
            //get filtered stories
            $query = $em->getRepository('AppBundle:Story')->createQueryBuilder('s')
                ->where('s.year0 >= :y0')
                ->andWhere('s.year0 <= :y1')
                ->setParameter('y0', $y0)
                ->setParameter ('y1',$y1)
                ->orderBy('s.storyOrder', 'ASC')
                ->getQuery();
            $stories = $query->getResult();

        } elseif (null != $request->query->get('kw')) {
            //getting results for search using functions above
            //will get stories data into an arry of strings, each element containing a concatenation of year + title + abstract + text for a story
            //this array will be the search corpus
            $kw = $request->query->get('kw'); //get search keywork

            //define special characters to be replaced in both keywords and corpus
            $unwanted_array = array(
                'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y',
                'ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T'
             );
            $kw1 = strtr( $kw, $unwanted_array ); //search key without unwanted chars

            $search = 1;
            $s = $em->getRepository('AppBundle:Story')
                ->findAll();
            $corpus = array();
            foreach ($s as $story) {
                //search in other lang.
                $locale = $request->getLocale();
                if ($locale != 'ro') {
                    $titleTrans = DefaultController::getTranslation($locale,'story',$story->getId(),'title');
                    $abstractTrans = DefaultController::getTranslation($locale,'story',$story->getId(),'abstract');
                    $textTrans = DefaultController::getTranslation($locale,'story',$story->getId(),'text');
                    $value = strtolower($titleTrans." ".$abstractTrans." ".$textTrans);
                } else {
                    $value = strtolower($story->getTitle()." ".$story->getAbstract()." ".$story->getText());
                }

                $value = strtr( $value, $unwanted_array );
                $corpus[] = $value;
            }
            array_unshift($corpus, "phoney");
            unset($corpus[0]);

            $query = $kw1; //search key

            $match_results = DefaultController::get_similar_documents($query,$corpus);
            /* algoritmul de cautare preluat
            echo '<pre>';
                print_r($corpus);
                print_r($match_results);
            echo '</pre>';
            exit();
            */
            //array keys are story ids
            $storyIds = array_keys($match_results);
            $stories = $em->getRepository('AppBundle:Story')
                ->findById($storyIds);

        } else {
            //get all stories
            $filter = 0;
/*
            $query = $em->getRepository('AppBundle:Story')->createQueryBuilder('s')
                ->select('s')
                ->orderBy('s.storyOrder', 'ASC')
                ->getQuery();
            $stories = $query->getResult();
*/
            $stories = $this->getDoctrine()->getManager()
                ->createQueryBuilder()->select('s, p') // key is to select both entities
                ->from('AppBundle:Story', 's')
                ->join('s.photos', 'p')
                ->orderBy('s.storyOrder','ASC')
                ->getQuery()->getResult();
        }


        return array("filter"=>$filter,"stories"=>$stories, "y0"=>$y0, "y1"=>$y1, "search"=>$search, "kw"=>$kw);
    }

    public function homeAction(Request $request)
    {
        /* get years from stories */
        $uniqueYears = DefaultController::getYears();

        /*get filter status and stories*/

        $fullArray = DefaultController::getStories($request);
        $filter = $fullArray['filter'];
        $stories = $fullArray['stories'];
        $y0 = $fullArray['y0'];
        $y1 = $fullArray['y1'];

        //get translations for titles, abstracts, texts and notes
        $locale = $request->getLocale();

        if ($locale != 'ro') {
            foreach ($stories as $story) {
                $titleTrans = DefaultController::getTranslation($locale,'story',$story->getId(),'title');

                $abstractTrans = DefaultController::getTranslation($locale,'story',$story->getId(),'abstract');

                $story->setTitle($titleTrans);
                $story->setAbstract($abstractTrans);


            }


        }




        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'stories'=>$stories,
            'filter'=>$filter,
            'y0'=>$y0,
            'y1'=>$y1,
            'years'=>$uniqueYears
        ]);
    }

    public function argumentAction(Request $request)
    {
        /* get years from stories */
        $uniqueYears = DefaultController::getYears();

        /*get filter status and stories*/
        //$filter = DefaultController::getStories($request)['filter'];
        //$stories = DefaultController::getStories($request)['stories'];
        //$y0 = DefaultController::getStories($request)['y0'];
        //$y1 = DefaultController::getStories($request)['y1'];

        return $this->render('default/argument.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            //'stories'=>$stories,
            //'filter'=>$filter,
            //'y0'=>$y0,
            //'y1'=>$y1,
            'years'=>$uniqueYears
        ]);
    }

    public function listAction(Request $request)
    {
        /* get years from stories */
        $uniqueYears = DefaultController::getYears();

        /*get filter status and stories*/
        $fullArray = DefaultController::getStories($request);

        $filter = $fullArray['filter'];
        $stories = $fullArray['stories'];
        $y0 = $fullArray['y0'];
        $y1 = $fullArray['y1'];
        $search = $fullArray['search'];
        $kw = $fullArray['kw'];

        $locale = $request->getLocale();
        if ($locale != 'ro') {
            foreach ($stories as $story) {
                $titleTrans = DefaultController::getTranslation($locale,'story',$story->getId(),'title');
                $story->setTitle($titleTrans);
            }

        }


        return $this->render('default/list.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'stories'=>$stories,
            'filter'=>$filter,
            'y0'=>$y0,
            'y1'=>$y1,
            'years'=>$uniqueYears,
            'search'=>$search,
            'kw'=>$kw
        ]);
    }



    public function showAction($no, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $storiesNo = $em->getRepository('AppBundle:Story')
            ->getStoriesCount();

        //$storiesNo = count($stories);

        $story = $em->getRepository('AppBundle:Story')
            ->findBy(
                array('storyOrder' => $no)
            );

        //get an array of available story positions
        $query = $em->getRepository('AppBundle:Story')->createQueryBuilder('s')
            ->select('s.storyOrder')
            ->orderBy('s.storyOrder', 'ASC')
            ->getQuery();
        $storyPositionsTemp = $query->getResult();

        $storyPositions = [];
        foreach ($storyPositionsTemp as $position) {
            $storyPositions[]=$position['storyOrder'];
        }

        /*
        $story = $em->getRepository('AppBundle:Story')
            ->getStory($no);
            */

        /*
        $poze = $story[0]->getPhotos();
        $photosArr = [];
        for ($i=0; $i < sizeof($poze) ; $i++) {
            $photosArr[] = array(
                "file" => $poze[$i]->getFile(),
                "note" => $poze[$i]->getNotes()
            );
        }
        */

        $locale = $request->getLocale();
        if ($locale != 'ro') {
            $titleTrans = DefaultController::getTranslation($locale,'story',$story[0]->getId(),'title');

            $story[0]->setTitle($titleTrans);
            $abstractTrans = DefaultController::getTranslation($locale,'story',$story[0]->getId(),'abstract');
            $story[0]->setAbstract($abstractTrans);
            $textTrans = DefaultController::getTranslation($locale,'story',$story[0]->getId(),'text');
            $story[0]->setText($textTrans);
            foreach ($story[0]->getPhotos() as $photo) {
                $noteTrans = DefaultController::getTranslation($locale,'photo',$photo->getId(),'notes');
                $photo->setNotes($noteTrans);
            }
        }


        $storyData = array(
            'id' => $story[0]->getId(),
            'title' => $story[0]->getTitle(),
            'abstract' => $story[0]->getAbstract(),
            'text' => $story[0]->getText(),
            'long' => $story[0]->getLongitude(),
            'lat' => $story[0]->getLatitude(),
            'y0' => $story[0]->getYear0(),
            'y1' => $story[0]->getYear1(),
            'order' => $story[0]->getStoryOrder(),
            'author' => $story[0]->getAuthor()->getNickname(),
            'photos'=> $story[0]->getPhotos()
        );


        //var_dump($storyData);
        //echo json_encode($storyData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );

        if (!$story) {
            throw $this->createNotFoundException(
                'No story found for id '.$no
            );
        }

        return $this->render('default/story.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'story'=>$storyData,
                    'storiesNo'=>$storiesNo,
                    'years'=>DefaultController::getYears(),
                    'storyPositions'=>$storyPositions
                ]);


    }
}
