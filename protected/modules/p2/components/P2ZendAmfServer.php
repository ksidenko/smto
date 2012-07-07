<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class P2ZendAmfServer
{
    public function getMenuData()
    {
		$pages = P2Page::getTree(1);
		$clients = SfClient::model()->findAll();
		
		$length = count(SfShowcase::model()->findAllByAttributes(array()));
		$showcaseId = rand(1, $length);
		$criteria = new CDbCriteria;
		$criteria->order = "rank"; 
		$showcases = SfProject::model()->with('sfShowcase')->findAllByAttributes(array('SfShowcaseId'=>$showcaseId), $criteria);
        
        $menuItems = array();
        
        foreach($pages AS $key=>$page) 
        {
        	$v = new VOMenuItem();
			$v->id = $page->id;
			$v->name = $page->name;
			$v->descriptiveName = $page->descriptiveName;
			$v->parentId = $page->parentId;
			$v->p2_infoId = $page->p2_infoId;
			$v->categoryId = substr (strrchr ($page->P2Info->customData, ":"), 1);
			$v->rank = $page->rank;
			$menuItems['mainMenuItems'][$key] = $v;
        }

        foreach($clients AS $key=>$client) 
        {
        	$c = new VOSubMenuItem();
			$c->id = $client->id;
			$c->descriptiveName = $client->name;
			$menuItems['clientMenuItems'][$key] = $c;
        }
    	
    	foreach($showcases AS $key=> $showcase) 
    		$menuItems['showcaseItems'][$key] = $this->addVOProjectItem($showcase);
    	
    	return $menuItems;
    }

	public function getProjectsByCategory($catId)
	{
        $cat = SfCategory::model()->with('sf_projects')->findByPk($catId);
    	$projectItems = array();
    	
    	foreach($cat->sf_projects AS $key=> $project)
    		$projectItems[$key] = $this->addVOProjectItem($project);
    	
    	return $projectItems;
	}

	public function getProjectsByClient($clientId)
	{
		$client = SfProject::model()->with('sfCategory')->findAllByAttributes(array('SfClientId'=>$clientId));
		$categories = SfCategory::model()->findAll();

    	$projectItems = array();
    	
    	foreach($categories AS $category) 
    	{
    		foreach($client AS $key=> $project) 
    		{
    			if($category->id == $project->sfCategory->id)
    			{
			    	$c = new VOSubMenuItem();
			    	$c->id = $category->id;
			    	$c->descriptiveName = $category->name;
			    	$projectItems[$category->name]['catMenuItem'] = $c;
					$projectItems[$category->name][$key] = $this->addVOProjectItem($project);      				
    			}
    		}
    	}
		return $projectItems;
	}

	private function addVOProjectItem($project)
	{
		$p = new VOProjectItem();
		$p->id = $project->id;
		$p->projectName = $project->name;
		$p->description = $project->description;
		$p->clientName = $project->sfClient->name;
		$p->sfClientId = $project->SfClientId;
		$p->sfCategoryId = $project->SfCategoryId;
		$p->previewFile = Yii::app()->controller->createAbsoluteUrl('/p2/p2File/image', array('id' => $project->mediaId0,'preset'=>'original'));
    	
    	for($i = 1; $i < 9; $i++)
    		if($project['mediaId'.$i]) 
    			$p->projectFiles[] = Yii::app()->controller->createAbsoluteUrl('/p2/p2File/image', array('id' => $project['mediaId'.$i],'preset'=>'original'));
    			
    	return $p;
	}

    public function findByPk($modelName, $pk)
    {
        $model = new $modelName;
        return $model->findAllByPk($pk);
    }

    public function findAllByAttributes($modelName, $cond = array())
    {
        $model = new $modelName;
        return $model->findAllByAttributes($cond);
    }

    public function getDate()
    {
        setlocale(LC_TIME, 'de_DE');
        return strftime('%A, %d. %B %Y');
    }
}

class VOMenuItem
{
    public $id;
    public $name;
    public $descriptiveName;
    public $parentId;
    public $p2_infoId;
    public $categoryId;
    public $rank;
}

class VOSubMenuItem
{
    public $id;
    public $descriptiveName;
}

class VOProjectItem
{
    public $id;
    public $projectName;
    public $description;
    public $clientName;
    public $sfClientId;
    public $sfCategoryId;
    public $previewFile;
    public $projectFiles;
}

?>