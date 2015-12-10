<?php

class WPLMS_Content_Templates {


	public static $instance;
    
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Content_Templates;
        return self::$instance;
    }

	private function __construct(){

	}

	function get_template($post_type,$type){
		switch($post_type){
			case 'question':
				$this->settings = array(
					'post_content'=>'',
					'meta_fields'=> array(
						'vibe_question_type'=>'',
						'vibe_question_options'=>'',
						'vibe_question_answer'=>'',
						'vibe_question_hint'=>'',
						'vibe_question_explaination'=>''
						)
					);
				self::get_question_templates($type);
			break;
		}

		return $this->settings;
	}

	function get_question_templates($type){

		switch($type){
			case 'single':
				$this->settings['post_content'] = 'Question Statement : Which is the largest continent in the World ?';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>array('Asia','America','Europe','Australia'),
						'vibe_question_answer'=>'1',
						'vibe_question_hint'=> 'Continent with Russia',
						'vibe_question_explaination'=>'A continent is one of several very large landmasses on Earth. They are generally identified by convention rather than any strict criteria, with up to seven regions commonly regarded as continents.'
					);
			break;
			case 'multiple':
				$this->settings['post_content'] = 'Question Statement : This question can have multiple answers.';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>array('Option 1','Option 2','Option 3','Option 4'),
						'vibe_question_answer'=>'2,3',
						'vibe_question_hint'=> ' The answer to this quesiton is 2,3',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
			case 'truefalse':
				$this->settings['post_content'] = 'Question Statement : True and False question type.';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_answer'=>'1',
						'vibe_question_hint'=> ' True',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
			case 'select':
				$this->settings['post_content'] = 'Question Statement : Select correct answer out of the following [select]';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>array('Option 1','Option 2','Option 3','Option 4'),
						'vibe_question_answer'=>'1',
						'vibe_question_hint'=> ' Option 1',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
			case 'sort':
				$this->settings['post_content'] = 'Question Statement : Arrange the below options in following order: 4,3,2,1';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>array('Option 1','Option 2','Option 3','Option 4'),
						'vibe_question_answer'=>'4,3,2,1',
						'vibe_question_hint'=> '4,3,2,1',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
			case 'match':
				$this->settings['post_content'] = 'Question Statement : Arrange the below options in following order: 4,3,2,1 <br />[match]<ul><li>First Order</li><li>Second Order</li><li>Third order</li><li>Fourth Order</li></ul>';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>array('Option 1','Option 2','Option 3','Option 4'),
						'vibe_question_answer'=>'4,3,2,1',
						'vibe_question_hint'=> '4,3,2,1',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
			case 'fillblank':
				$this->settings['post_content'] = 'Question Statement : Fill in the blank [fillblank]';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>array(),
						'vibe_question_answer'=>'somevalue',
						'vibe_question_hint'=> 'some value',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
			case 'smalltext':
				$this->settings['post_content'] = 'Question Statement : Enter the answer in below text box';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>'',
						'vibe_question_answer'=>'some answer',
						'vibe_question_hint'=> 'some hint',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
			case 'largetext':
				$this->settings['post_content'] = 'Question Statement :Enter the answer in below text area';
				$this->settings['meta_fields'] = array(
						'vibe_question_type'=> $type,
						'vibe_question_options'=>'',
						'vibe_question_answer'=>'some answer',
						'vibe_question_hint'=> 'some hint',
						'vibe_question_explaination'=>'Some explaination to this question.'
					);
			break;
		}
	}
}