<?php
/**
 * Consult documentation on http://agiletoolkit.org/learn 
 */
class Frontend extends ApiFrontend {
    function init(){
        parent::init();
        // Keep this if you are going to use database on all pages
        $this->dbConnect();
        $this->requires('atk','4.2.0');

        // This will add some resources from atk4-addons, which would be located
        // in atk4-addons subdirectory.
        $this->addLocation('atk4-addons',array(
                    'php'=>array(
                        'mvc',
                        'misc/lib',
                        )
                    ))
            ->setParent($this->pathfinder->base_location);

        // A lot of the functionality in Agile Toolkit requires jUI
        $this->add('jUI');

        // Initialize any system-wide javascript libraries here
        // If you are willing to write custom JavaScript code,
        // place it into templates/js/atk4_univ_ext.js and
        // include it here
        $this->js()
            ->_load('atk4_univ')
            ->_load('ui.atk4_notify')
            ;

        $this->today = date('Y-m-d',strtotime($this->recall('current_date',date('Y-m-d'))));
        $this->now = date('Y-m-d H:i:s',strtotime($this->recall('current_date',date('Y-m-d H:i:s'))));
       
        // If you wish to restrict access to your pages, use BasicAuth class
        $auth=$this->add('BasicAuth');
        $auth->setModel('Staff','username','password');
            // ->allow('demo','demo')
            // use check() and allowPage for white-list based auth checking
            $auth->check();
            // ;



        $this->currentBranch = $this->add('Model_Branch')->getCurrent();
        $this->currentSession = $this->add('Model_Session')->getCurrent();

        if($this->auth->isLoggedIn()){
            // $this->template->set('current_branch_name',$this->currentBranch['name']);
            // $this->template->set('current_staff',$this->api->auth->model['name']);
            // $this->template->set('current_session',$this->currentSession['name']);
            // $this->template->set('current_date',$this->recall('current_date'));
            $current_date='<span style="color:green">'.$this->api->today.'</span>';
            if(strtotime($this->today)!=strtotime(date('Y-m-d')))
                    $current_date='<span style="color:red">'.$this->api->today.'</span>';

            
            $current_session='<span style="color:green">'.$this->api->currentSession['name'].'</span>';
            if($this->api->currentSession->id != $this->add('Model_Session')->getLast()->get('id'))
                $current_session='<span style="color:red">'.$this->api->currentSession['name'].'</span>';

            $v=$this->add('View',null,'WelcomeBlock');
            $v->setStyle('width','300px');
            $v->setHtml('<b>Welcome! </b>'.$this->api->auth->model['name'].'<br/>'
                .'Current Branch : '.$this->currentBranch['name'].'<br/>'.
                'Current Session : '.$current_session.'<br/>'.
                'Current Date : '.$current_date.'<br/>'
                );

            $btn=$this->add('Button',null,'button')->set('Update Branch in Transaction');
            $btn1=$this->add('Button',null,'button')->set('Change Date');
            $v->addClass('welcome-block');
            $v->js('reload')->reload();

            $btn->js('click')->univ()->newWindow($this->api->url('correcttransaction'));
            $btn1->js('click')->univ()->frameURL("Change Date",$this->api->url('setdate'));

            // $btn=$this->add('Button')->set('Set Date');
        }

        // This method is executed for ALL the pages you are going to add,
        // before the page class is loaded. You can put additional checks
        // or initialize additional elements in here which are common to all
        // the pages.

        // Menu:

        // If you are using a complex menu, you can re-define
        // it and place in a separate class
        $this->add('Menu',null,'Menu')
            // ->addMenuItem('index','Welcome')
            ->addMenuItem('master','Masters')
            ->addMenuItem('master_student_main','Student Management')
            ->addMenuItem('fees_main','Fees Management')
            ->addMenuItem('reports_daybook','Day Book')
            ->addMenuItem('accounts','General Accounting ')
            ->addMenuItem('transport_main','Transport Management ')
            ->addMenuItem('library_main','Library Management ')
            ->addMenuItem('exam_main','Exam Management ')
            ->addMenuItem('reports_main','Reports ')
            ->addMenuItem('stock_main','Stock ')
            ->addMenuItem('sms_main','SMS ')
            ->addMenuItem('logout')
            ;

        $this->addLayout('UserMenu');
    }
    
    function setDate($date){
        $this->api->memorize('current_date',$date);
        $this->now = date('Y-m-d H:i:s',strtotime($date));
        $this->today = date('Y-m-d',strtotime($date));
        $log=$this->add('Model_Log');
        $log->createNew("software Date change Manualy");
        $log->save();
    }

    function nextDate($date=null){
        if(!$date) $date = $this->api->today;
        $date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " +1 DAY"));    
        return $date;
    }

    function my_date_diff($d1, $d2){
        $d1 = (is_string($d1) ? strtotime($d1) : $d1);
        $d2 = (is_string($d2) ? strtotime($d2) : $d2);

        $diff_secs = abs($d1 - $d2);
        $base_year = min(date("Y", $d1), date("Y", $d2));

        $diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
        return array(
        "years" => date("Y", $diff) - $base_year,
        "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
        "months" => date("n", $diff) - 1,
        "days_total" => floor($diff_secs / (3600 * 24)),
        "days" => date("j", $diff) - 1,
        "hours_total" => floor($diff_secs / 3600),
        "hours" => date("G", $diff),
        "minutes_total" => floor($diff_secs / 60),
        "minutes" => (int) date("i", $diff),
        "seconds_total" => $diff_secs,
        "seconds" => (int) date("s", $diff)
        );
    }



}
