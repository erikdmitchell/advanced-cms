<?php

require_once( dirname(__FILE__).'/class.OAuth.php' );
require_once( dirname(__FILE__).'/class.BitBucketAuth.php' );

/**
 *	class BitBucket
 *	contains only API functions.
 *	extends from BitBucketAuth, which have code to Auth with OAuth 1.0a
 *
 *	list of available operations
 *	- repositories: get list of repositories (my, all)
 *	- repositories: get repository info
 *	- repositories: get repository clean URL
 *	- events: commit history info
 *	- repositories: create repository
 *	- repositories: remove repository
 *  - repositories: get tags/branches list
 *  - users: register new user in bitbucket
 *  - privileges: granting privileges to user
 *  - privileges: remove privileges from user
 *  - privileges: check users with privileges
 */
class BitBucket extends BitBucketAuth {
	
	private $repository_domain = 'https://bitbucket.org';
	
	/**
	 *	get full list of repositories you own
	 * @param string $username repository owner username -- ADDED EM
	 */
	public function get_my_repositories($username) {
		
		$repos = array();
		$res = $this->request_api('GET', 'users/'.$username);
		//pa($res,1);
		if( !empty($res['response']) ){
			$info = json_decode($res['response']);
			if (isset($info->repositories)) : // IF STATEMENT ADDED BY EM
				foreach($info->repositories as $repo){
					$repos[ $repo->slug ] = $repo;
				}
			endif;
		}
		
		//pa($repos,1);
		return $repos;
	}
	
	/**
	 *	get list of repositories you have access to
	 */
	public function get_all_repositories(){
		$repos = array();
		$res = $this->request_api('GET', 'user/repositories');
		if( !empty($res['response']) ){
			$_repos = json_decode($res['response']);
			foreach($_repos as $repo){
				$repos[ $repo->slug ] = $repo;
			}
		}
		
		//pa($repos,1);
		return $repos;
	}
	
	/**
	 *	get repository info
	 *	@param  string  $slug     repository slug
	 *	@param  string  $username   repository owner usernmae
	 *	@return object    repository object
	 */
	public function get_repository_info( $slug, $username = '' ){
		if( empty($username) ) $username = $this->username;
		
		$res = $this->request_api('GET', 'repositories/'.$username.'/'.$slug.'/');
		if( $res['status'] == 200 ){
			$info = json_decode($res['response']);
			return $info;
		}
		
		return NULL;
	}
	
	/**
	 *	get repository info
	 *	@param  string  $slug     repository slug
	 *	@param  string  $username   repository owner usernmae
	 *	@return string    repository clone URL
	 */
	public function get_repository_clone_url( $slug, $username = '' ){
		if( empty($username) ) $username = $this->username;
		
		return $this->repository_domain.'/'.$username.'/'.$slug.'.git';
	}
	
	/**
	 *	get repository current branches and tags
	 *	@param  string  $slug     repository slug
	 *	@param  string  $username   repository owner usernmae
	 *	@return array   list of tags and branches
	 */
	public function get_repository_tree( $slug, $username = '' ){
		if( empty($username) ) $username = $this->username;
		
		$result = array(
			'branches' => array(),
			'tags' => array(),
		);
		
		$res = $this->request_api('GET', 'repositories/'.$username.'/'.$slug.'/branches/');
		if( $res['status'] == 200 ){
			$branches = json_decode($res['response']);
			foreach($branches as $branch){
				unset($branch->files);
				$result['branches'][$branch->branch] = $branch;
			}
		}

		$res = $this->request_api('GET', 'repositories/'.$username.'/'.$slug.'/tags/');
		if( $res['status'] == 200 ){
			$tags = json_decode($res['response']);
			foreach($tags as $tag){
				$result['tags'][$tag->tag] = $tag;
			}
		}
		//pa($res,1);
		
		//pa($result,1);
		return $result;
	}
	
	/** NEEDS WORK EM
	 *	get repository events history
	 *	@param  string  $slug     repository slug
	 *	@param  string  $username   repository owner usernmae
	 *	@param  array   $params     you can set "type" filter, "limit" or "start" number for pagination
	 *	@return array     events history array
	 */
	public function get_commit_history($slug, $username = '', $params = array()){

		$defaults = array(
			'type' => 'commit',
			'start' => '0', // offset for pagination
			'limit' => 25, // limit of rows to get
		);
		
		$params = array_merge($defaults, $params);

		if( empty($username) ) $username = $this->username;
		
		$res = $this->request_api('GET', 'repositories/'.$username.'/'.$slug.'/events/', $params);
		//pa($res,1);

		if( $res['status'] == 200 ){
			$response = json_decode($res['response']);
			if( $response->count > 0 ){
				$events = array();
				foreach($response->events as $event){
					unset($event->repository);
					$events[] = $event;
				}
				
				//pa($events);
				return $events;
			}
		}
		
		return NULL;
	}
	
	//----	ADDED EM ----//
	/** 
	 *	get file and it contents in a repository
	 *	@param  string  $slug     repository slug
	 *	@param  string  $path     file with, extension (ie sample.php)
	 *	@param  string  $username   repository owner usernmae
	 *	@param  array   $revision    a value representing the revision or branch to list.
	 *	@return array     file array
	 */
	public function get_single_file($slug,$path,$username='',$revision='') {
		if( empty($username) ) $username = $this->username;
		if( empty($revision) ) $revision = 'master';

		$res=$this->request_api('GET','repositories/'.$username.'/'.$slug.'/raw/'.$revision.'/'.$path);

		if ($res['status']==200) :
			return $res;
		endif;
		
		return false;
	}

	/** 
	 *	get file and it contents in a repository
	 *	@param  string  $slug     repository slug
	 *	@param  string  $username   repository owner usernmae
	 *	@return array     changesets array
	 */
	public function get_changesets_list($slug,$username='') {
		if( empty($username) ) $username = $this->username;

		$res=$this->request_api('GET','repositories/'.$username.'/'.$slug.'/changesets');

		if ($res['status']==200) :
			return $res;
		endif;
		
		return false;
	}	

	/** 
	 *	get file and it contents in a repository
	 *	@param  string  $slug     repository slug
	 *	@param  string  $username   repository owner usernmae
	 *	@param  array   $revision    a value representing the revision or branch to list.
	 *	@return string	url
	 */
	public function get_zip_url($slug='',$username='',$zip_name='',$revision='master') {
		// we need to get our repo and download it and mod url
		// in the future only do this if private, otherwise leave as url
		
		if ($local_zip_url=$this->request_api_zip('GET','https://bitbucket.org/'.$username.'/'.$slug.'/get/'.$revision.'.zip', array(), $slug))
			return $local_zip_url;
				
		return $url;
	}	

}
?>
