<?php
App::uses('AppController', 'Controller');
/*
 * This class is used as the index class which is called first when the site is loaded
 */
class OptestController extends AppController {
    public $uses = array('User', 'PaperSetMaster','UserScoreMaster','FinalPaper','AnswerMaster');
    public $components = array('Session');
   /*
    * this is index function to load the landing page which is login page.
    */
    function index(){
        $this->layout = "optest";
        $this->set('title','Optest');
        
        if($this->Session->check('errorMsg'))
        {
            $errorMsg = $this->Session->read('errorMsg');
            $this->Session->delete('errorMsg');
            $this->set('errorMsg',$errorMsg);
        }
        
        if($this->Session->check('userLog'))
        {
            return $this->redirect(array('controller' => 'optest', 'action' => 'home'));
        }
    }
    
    /*
     * This function is used to refresh or load captcha values
     */
    function loadcaptcha(){
        $this->layout = false;
        $characters = '123456789';
        $signs = '-+';
        $sign = $signs[rand(0, strlen($signs) - 1)];;
        
        $var1 = $characters[rand(0, strlen($characters) - 1)];
        $var2 = $characters[rand(0, strlen($characters) - 1)];
        if($sign == '+')
        {
            $answer = $var1 + $var2;
            $signAnswer = '+';
        }
        if($sign == '-')
        {
           if($var1 > $var2)
            {
                $answer = $var1 - $var2;
                $signAnswer = '-';
            }
            else
            {
                $temp = $var1;
                $var1=$var2;
                $var2=$temp;
                $answer = $var1 - $var2;
                $signAnswer = '-';
            }
        }
        if($sign == '*')
        {
            $answer = $var1 * $var2;
            $signAnswer = 'X';
        }
        $this->set('var1',$var1);
        $this->set('var2',$var2);
        $this->set('signs',$signAnswer);
        $this->Session->write('answer',$answer);
    }
    
    /*
     * First page after login
     */
    function home()
    {
        if($this->Session->check('userLog'))
        {
            $this->layout = "optest";
            $userDetail = $this->Session->read('userLog');
            
            $this->set('title','User Portal');
            
            $this->set('user',$userDetail);
        }
        else
        {
            return $this->redirect(array('controller' => 'optest', 'action' => 'index'));
        }
    }
    
    /*
     *bank prepartion action where the student will see there list of papers with option of giving test and will see the option
     *that he has already given that paper
     */
    function bpa()
    {
        if($this->Session->check('userLog'))
        {
            $this->layout = "optest";
            $userDetail = $this->Session->read('userLog');
            $this->set('title','Banking Preparation Area');
            $userData = $this->Session->read('userLog');
            $paper_set = $this->PaperSetMaster->find('all');
            $score_set = $this->UserScoreMaster->find('all',array('conditions'=>array('user_id'=>$userData['id'])));
            $scoreArray = array();
            $in_score = array();
            foreach($score_set as $value)
            {
                $scoreArray[$value['UserScoreMaster']['psm_id']] = $value['UserScoreMaster'];
                $in_score[] = $value['UserScoreMaster']['psm_id'];
            }
            $this->set('papers', $paper_set);
            $this->set('scores', $scoreArray);
            $this->set('in_scores', $in_score);
        }
        else
        {
            return $this->redirect(array('controller' => 'optest', 'action' => 'index'));
        }
    }
    
    /*
     *this action will count the particular students rank in all and in same institute if student under institute
     */
    function rank()
    {
        if($this->Session->check('userLog'))
        {
            $this->layout = "optest";
            $userDetail = $this->Session->read('userLog');
            
            $this->set('title','Your Rank');
        }
        else
        {
            return $this->redirect(array('controller' => 'optest', 'action' => 'index'));
        }
    }
    
    /*this action will start the test for the students*/
    function usertest($id = '',$paperId = '')
    {
        if($paperId != '')
        {
            $this->Session->write('paperId',$paperId);
        }
        if($this->Session->check('userLog'))
        {
            if($this->Session->check('language'))
            {
                return $this->redirect(array('controller' => 'optest', 'action' => 'optexam'));
            }
            $this->layout = "usertest";
            $userDetail = $this->Session->read('userLog');
            $this->set('next',$id);
            $this->set('title','OPT Examination');
        }
        else
        {
            return $this->redirect(array('controller' => 'optest', 'action' => 'index'));
        }
    }
    
    /*function to beign the test*/
    function optexam()
    {
        if($this->Session->check('userLog') && $this->Session->check('language'))
        {
            $this->layout = "usertest";
            $this->set('language',$this->Session->read('language'));
            $this->set('title','OPT Examination');
            $paper_id = $this->Session->read('paperId');
            $paperData = $this->FinalPaper->find('all',array('conditions'=>array('pm_id'=>$paper_id)));
            $paperSetMaster = $this->PaperSetMaster->find('first',array('conditions'=>array('paper_set_id'=>$paper_id)));
            //print_r($paperSetMaster);
            //print_r($paperData);
            $this->set('paper',$paperData);
            $sectionTab = explode("#",$paperSetMaster['PaperSetMaster']['question_set']);
            if($this->Session->check('time') == false)
            {
                $time_limit = explode(".",$paperSetMaster['PaperSetMaster']['time_limit']);
                $time_limit[] = '59';
                $this->Session->write('time',$time_limit);
            }
            $this->set('time_limit',$this->Session->read('time'));
        }
        else
        {
            return $this->redirect(array('controller' => 'optest', 'action' => 'index'));
        }
        
    }
    
    /*create session for language*/
    function checkdata()
    {
        if($this->request->data)
        {
            $this->Session->write('language',$this->request->data['language_session']);
            return 1;
        }
        else
        {
            return 0;
        }
        die;
    }
}
?>