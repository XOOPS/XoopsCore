<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id $
 */

class XoopsApi extends XoopsXmlRpcApi
{
    /**
     * @param array $params
     * @param XoopsXmlRpcResponse $response
     * @param XoopsModule $module
     */
    public function __construct(array &$params, XoopsXmlRpcResponse &$response, XoopsModule &$module)
    {
        parent::__construct($params, $response, $module);
    }

    /**
     * @return void
     */
    public function newPost()
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            if (!$fields = $this->_getPostFields(null, $this->params[0])) {
                $this->response->add(new XoopsXmlRpcFault(106));
            } else {
                $missing = array();
                foreach ($fields as $tag => $detail) {
                    if (!isset($this->params[3][$tag])) {
                        $data = $this->_getTagCdata($this->params[3]['xoops_text'], $tag, true);
                        if (trim($data) == ''){
                            if ($detail['required']) {
                                $missing[] = $tag;
                            }
                        } else {
                            $post[$tag] = $data;
                        }
                    } else {
                        $post[$tag] = $this->params[3][$tag];
                    }
                }
                if (count($missing) > 0) {
                    $msg = '';
                    foreach ($missing as $m) {
                        $msg .= '<'.$m.'> ';
                    }
                    $this->response->add(new XoopsXmlRpcFault(109, $msg));
                } else {
                    // will be removed... don't worry if this looks bad
                    if (!XoopsLoad::loadFile(\XoopsBaseConfig::get('root-path').'/modules/news/class/class.newsstory.php', true)) {
						$this->response->add(new XoopsXmlRpcFault(103));
						return;
					}
                    $story = new NewsStory();
                    $error = false;
                    if ((int)($this->params[4]) > 0) {
                        if (!$this->_checkAdmin()) {
                            // non admin users cannot publish
                            $error = true;
                            $this->response->add(new XoopsXmlRpcFault(111));
                        } else {
                            $story->setType('admin');
                            $story->setApproved(true);
                            $story->setPublished(time());
                        }
                    } else {
                        if (!$this->_checkAdmin()) {
                            $story->setType('user');
                        } else {
                            $story->setType('admin');
                        }
                    }
                    if (!$error) {
                        if (isset($post['categories']) && !empty($post['categories'][0])) {
                            $story->setTopicId((int)($post['categories'][0]['categoryId']));
                        } else {
                            $story->setTopicId(1);
                        }
                        $story->setTitle(addslashes(trim($post['title'])));
                        if (isset($post['moretext'])) {
                            $story->setBodytext(addslashes(trim($post['moretext'])));
                        }
                        if (!isset($post['hometext'])) {
                            $story->setHometext(addslashes(trim($this->params[3]['xoops_text'])));
                        } else {
                            $story->setHometext(addslashes(trim($post['hometext'])));
                        }
                        $story->setUid($this->user->getVar('uid'));
                        $story->setHostname($_SERVER['REMOTE_ADDR']);
                        if (!$this->_checkAdmin()) {
                            $story->setNohtml(1);
                        } else {
                            $story->setNohtml(0);
                        }
                        $story->setNosmiley(0);
                        $story->setNotifyPub(1);
                        $story->setTopicalign('R');
                        $ret = $story->store();
                        if (!$ret) {
                            $this->response->add(new XoopsXmlRpcFault(106));
                        } else {
                            $this->response->add(new XoopsXmlRpcString($ret));
                        }
                    }
                }
            }
        }
    }

    function editPost()
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            if (!$fields = $this->_getPostFields($this->params[0])) {
            } else {
                $missing = array();
                foreach ($fields as $tag => $detail) {
                    if (!isset($this->params[3][$tag])) {
                        $data = $this->_getTagCdata($this->params[3]['xoops_text'], $tag, true);
                        if (trim($data) == ''){
                            if ($detail['required']) {
                                $missing[] = $tag;
                            }
                        } else {
                            $post[$tag] = $data;
                        }
                    } else {
                        $post[$tag] = $this->params[3][$tag];
                    }
                }
                if (count($missing) > 0) {
                    $msg = '';
                    foreach ($missing as $m) {
                        $msg .= '<'.$m.'> ';
                    }
                    $this->response->add(new XoopsXmlRpcFault(109, $msg));
                } else {
                    // will be removed... don't worry if this looks bad
                    if (!XoopsLoad::loadFile(\XoopsBaseConfig::get('root-path').'/modules/news/class/class.newsstory.php', true)) {
						$this->response->add(new XoopsXmlRpcFault(103));
						return;
					}
                    $story = new NewsStory($this->params[0]);
                    $storyid = $story->storyid();
                    if (empty($storyid)) {
                        $this->response->add(new XoopsXmlRpcFault(106));
                    } elseif (!$this->_checkAdmin()) {
                        $this->response->add(new XoopsXmlRpcFault(111));
                    } else {
                        $story->setTitle(addslashes(trim($post['title'])));
                        if (isset($post['moretext'])) {
                            $story->setBodytext(addslashes(trim($post['moretext'])));
                        }
                        if (!isset($post['hometext'])) {
                            $story->setHometext(addslashes(trim($this->params[3]['xoops_text'])));
                        } else {
                            $story->setHometext(addslashes(trim($post['hometext'])));
                        }
                        if ($this->params[4]) {
                            $story->setApproved(true);
                            $story->setPublished(time());
                        }
                        $story->setTopicalign('R');
                        if (!$story->store()) {
                            $this->response->add(new XoopsXmlRpcFault(106));
                        } else {
                            $this->response->add(new XoopsXmlRpcBoolean(true));
                        }
                    }
                }
            }
        }
    }

    function deletePost()
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            if (!$this->_checkAdmin()) {
                $this->response->add(new XoopsXmlRpcFault(111));
            } else {
                // will be removed... don't worry if this looks bad
				if (!XoopsLoad::loadFile(\XoopsBaseConfig::get('root-path').'/modules/news/class/class.newsstory.php', true)) {
					$this->response->add(new XoopsXmlRpcFault(103));
					return;
				}
                $story = new NewsStory($this->params[0]);
                if (!$story->delete()) {
                    $this->response->add(new XoopsXmlRpcFault(106));
                } else {
                    $this->response->add(new XoopsXmlRpcBoolean(true));
                }
            }
        }
    }

    // currently returns the same struct as in metaWeblogApi
    function getPost($respond=true)
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            // will be removed... don't worry if this looks bad
			if (!XoopsLoad::loadFile(\XoopsBaseConfig::get('root-path').'/modules/news/class/class.newsstory.php', true)) {
				$this->response->add(new XoopsXmlRpcFault(103));
				return;
			}
            $story = new NewsStory($this->params[0]);
            $ret = array('uid' => $story->uid(), 'published' => $story->published(), 'storyid' => $story->storyId(), 'title' => $story->title('Edit'), 'hometext' => $story->hometext('Edit'), 'moretext' => $story->bodytext('Edit'));
            if (!$respond) {
                return $ret;
            } else {
                if (!$ret) {
                    $this->response->add(new XoopsXmlRpcFault(106));
                } else {
                    $struct = new XoopsXmlRpcStruct();
                    $content = '';
                    foreach ($ret as $key => $value) {
                        switch($key) {
                        case 'uid':
                            $struct->add('userid', new XoopsXmlRpcString($value));
                            break;
                        case 'published':
                            $struct->add('dateCreated', new XoopsXmlRpcDatetime($value));
                            break;
                        case 'storyid':
                            $struct->add('postid', new XoopsXmlRpcString($value));
                            $struct->add('link', new XoopsXmlRpcString(\XoopsBaseConfig::get('url').'/modules/news/article.php?item_id='.$value));
                            $struct->add('permaLink', new XoopsXmlRpcString(\XoopsBaseConfig::get('url').'/modules/news/article.php?item_id='.$value));
                            break;
                        case 'title':
                            $struct->add('title', new XoopsXmlRpcString($value));
                            break;
                        default :
                            $content .= '<'.$key.'>'.trim($value).'</'.$key.'>';
                            break;
                        }
                    }
                    $struct->add('description', new XoopsXmlRpcString($content));
                    $this->response->add($struct);
                }
            }
        }
    }

    function getRecentPosts($respond=true)
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
			if (!XoopsLoad::loadFile(\XoopsBaseConfig::get('root-path').'/modules/news/class/class.newsstory.php', true)) {
				$this->response->add(new XoopsXmlRpcFault(103));
				return;
			}
            if (isset($this->params[4]) && (int)($this->params[4]) > 0) {
                $stories = NewsStory::getAllPublished((int)($this->params[3]), 0, $this->params[4]);
            } else {
                $stories = NewsStory::getAllPublished((int)($this->params[3]));
            }
            $scount = count($stories);
            $ret = array();
            for ($i = 0; $i < $scount; ++$i) {
                $ret[] = array('uid' => $stories[$i]->uid(), 'published' => $stories[$i]->published(), 'storyid' => $stories[$i]->storyId(), 'title' => $stories[$i]->title('Edit'), 'hometext' => $stories[$i]->hometext('Edit'), 'moretext' => $stories[$i]->bodytext('Edit'));
            }
            if (!$respond) {
                return $ret;
            } else {
                if (count($ret) == 0) {
                    $this->response->add(new XoopsXmlRpcFault(106, 'Found 0 Entries'));
                } else {
                    $arr = new XoopsXmlRpcArray();
                    $count = count($ret);
                    for ($i = 0; $i < $count; ++$i) {
                        $struct = new XoopsXmlRpcStruct();
                        $content = '';
                        foreach($ret[$i] as $key => $value) {
                            switch($key) {
                            case 'uid':
                                $struct->add('userid', new XoopsXmlRpcString($value));
                                break;
                            case 'published':
                                $struct->add('dateCreated', new XoopsXmlRpcDatetime($value));
                                break;
                            case 'storyid':
                                $struct->add('postid', new XoopsXmlRpcString($value));
                                $struct->add('link', new XoopsXmlRpcString(\XoopsBaseConfig::get('url').'/modules/news/article.php?item_id='.$value));
                                $struct->add('permaLink', new XoopsXmlRpcString(\XoopsBaseConfig::get('url').'/modules/news/article.php?item_id='.$value));
                                break;
                            case 'title':
                                $struct->add('title', new XoopsXmlRpcString($value));
                                break;
                            default :
                                $content .= '<'.$key.'>'.trim($value).'</'.$key.'>';
                                break;
                            }
                        }
                        $struct->add('description', new XoopsXmlRpcString($content));
                        $arr->add($struct);
                        unset($struct);
                    }
                    $this->response->add($arr);
                }
            }
        }
    }

    function getCategories($respond=true)
    {
        global $xoopsDB;
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
			if (!XoopsLoad::loadFile(\XoopsBaseConfig::get('root-path').'/class/xoopstopic.php', true)) {
				$this->response->add(new XoopsXmlRpcFault(103));
				return;
			}
            //$this->db = xoopsDB;
            $xt = new XoopsTopic($xoopsDB->prefix('topics'));
            $ret = $xt->getTopicsList();
            if (!$respond) {
                return $ret;
            } else {
                if (count($ret) == 0) {
                    $this->response->add(new XoopsXmlRpcFault(106, 'Found 0 Entries'));
                } else {
                    $arr = new XoopsXmlRpcArray();
                    foreach ($ret as $topic_id => $topic_vars) {
                        $struct = new XoopsXmlRpcStruct();
                        $struct->add('categoryId', new XoopsXmlRpcString($topic_id));
                        $struct->add('categoryName', new XoopsXmlRpcString($topic_vars['title']));
                        $struct->add('categoryPid', new XoopsXmlRpcString($topic_vars['pid']));
                        $arr->add($struct);
                        unset($struct);
                    }
                    $this->response->add($arr);
                }
            }
        }
    }
}
?>
