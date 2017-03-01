<?php 

namespace App\Controllers;

use SphinxClient;

class SphinxSearchController extends ControllerBase {
	//TODO 服务器安装sphinxsearch成功，生成索引成功，就差连接搜索尝试

	/**
	 * 用户前台页面全局搜索
	 * @author Masterton
	 * @param $data 用户搜索输入的关键字信息
	 * @return $result 搜索到的相关信息数据
	 *
	 */
	public function query(\Slim\Http\Request $request, \Slim\Http\Response $response, $args=[]) {
		
		$params = $request->getParams();

		$data = array_get($params, 'data');
		$data = json_decode($data, true);

		if(!empty($data['value'])) {
			//根据关键词获得对应的检索结果
			$retrieval = $this->sphinxfront($data);
			if(!empty($retrieval)) {
				//根据检索结果获取具体信息
				$result = $this->lookup($retrieval);
				$ret = msg($result, '查询成功');
			}else {
				$ret = msg([], '未查询到相应的数据');
			}
		}else {
			$ret = msg([], '输入为空', 1);
		}
		return $response->withJson($ret);
	}

	/**
	 * 用户后台管理页面全局搜索
	 * @param $data 用户搜索输入的关键字信息
	 * @return $result 搜索到的相关信息数据
	 *
	 */
	public function search(\Slim\Http\Request $request, \Slim\Http\Response $response, $args=[]) {
		$params = $request->getParams();

		$data = array_get($params, 'data');
		//$data = json_decode($data, true);

		if(!empty($data['value'])) {
			//根据关键词获得对应的检索结果
			$retrieval = $this->sphinxback($data);
			if(!empty($retrieval)) {
				//根据检索结果获取具体信息
				$result = $this->lookup($retrieval);
				$ret = msg($result, '查询成功');
			}else {
				$ret = msg([], '未查询到相应的数据');
			}
		}else {
			$ret = msg([], '输入为空', 1);
		}
		return $response->withJson($ret);
	}

	/**
	 * 获取用户搜索关键字查询到的索引结果（后台）
	 * @param $data 用户输入的关键字
	 * @return $result 查询到的索引结果
	 *
     */
	public function sphinxback($data) {

		//实例化一个sphinx全中文检索
		$sphinx = new SphinxClient;

		//设置searchd的主机名和TCP端口
		$sphinx->setServer("localhost", 9312);

		//设置全文查询的匹配模式
		//$sphinx->setMatchMode(SPH_MATCH_ANY);

		//设置最大查询时间
		$sphinx->setMaxQueryTime(3);

		//将查询添加到多查询批
		$sphinx->AddQuery($data['value'], "workflow");
		$sphinx->AddQuery($data['value'], "user");
		$sphinx->AddQuery($data['value'], "flow");

		//运行一批搜索查询
		$results = $sphinx->RunQueries ();

		//存放检索出来的数据的对应数据表中的列的ID
		$result = [];
		$total = 0;
		for ($i = 0; $i < count($results); $i++) { 
			//如果当前表中检索到的数据不为空就放入数组
			if(!empty($results[$i]['matches'])){
				$result[$i] = array_keys($results[$i]['matches']);
			}
			$total += $results[$i]['total'];
		}

		return $result;
	}


	/**
	 * 获取用户搜索关键字查询到的索引结果（前台）
	 * @param $data 用户输入的关键字
	 * @return $result 查询到的索引结果
	 *
     */
	public function sphinxfront($data) {

		//实例化一个sphinx全中文检索
		$sphinx = new SphinxClient;

		//设置searchd的主机名和TCP端口
		$sphinx->setServer("localhost", 9312);

		//设置全文查询的匹配模式
		//$sphinx->setMatchMode(SPH_MATCH_ANY);

		//设置最大查询时间
		$sphinx->setMaxQueryTime(3);

		//将查询添加到多查询批
		$sphinx->AddQuery($data['value'], "workflow");
		//$sphinx->AddQuery($data['value'], "user");
		//$sphinx->AddQuery($data['value'], "flow");

		//运行一批搜索查询
		$results = $sphinx->RunQueries ();

		//存放检索出来的数据的对应数据表中的列的ID
		$result = [];
		$total = 0;
		for ($i = 0; $i < count($results); $i++) { 
			//如果当前表中检索到的数据不为空就放入数组
			if(!empty($results[$i]['matches'])){
				$result[$i] = array_keys($results[$i]['matches']);
			}
			$total += $results[$i]['total'];
		}

		return $result;
	}

	/**
	 * 根据检索结果在对应的表中查询具体结果并返回
	 * @param $data 检索到的结果集合
	 * @return $result 查询到的具体数据集合
	 *
	 */
	public function lookup($data) {

		//定义一个空数组拿来存放查询结果
		$result = [];
		//查询工作流程
		if(!empty($data[0])) {
			$select = ['work_flow', 'model_flow', 'tags'];
			$query = \App\Models\WorkFlow::whereIn('id', $data[0])->select($select)->get();
			array_push($result, $query);
		}
		//查询用户
		if(!empty($data[1])) {
			$select = ['oname', 'type', 'ucode', 'post', 'uname', 'name','phone'];
			$query = \App\Models\User::whereIn('id', $data[1])->select($select)->get();
			array_push($result, $query);
		}
		//查询流程模型
		if(!empty($data[2])) {
			$select = ['flow_num', 'flow_name', 'flow_desc'];
			$query = \App\Models\Flow::whereIn('id', $data[2])->select($select)->get();
			array_push($result, $query);
		}

		return $result;
	}
}