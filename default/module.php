<?php
/**
 * 侧边栏组件、页面模块
 */
if (!defined('EMLOG_ROOT')) {
	exit('error!');
}
?>
<?php
/**
 * 侧边栏：链接
 */
function widget_link($title) {
	global $CACHE;
	$link_cache = $CACHE->readCache('link');
	//if (!blog_tool_ishome()) return;#只在首页显示友链去掉双斜杠注释即可
	?>
    <div class="widget shadow-theme">
        <div class="widget-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="widget-list no-margin-bottom unstyle-li">
			<?php foreach ($link_cache as $value): ?>
                <li><a href="<?php echo $value['url']; ?>" target="_blank"><?php echo $value['link']; ?></a></li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：个人资料
 */
function widget_blogger($title) {
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$name = $user_cache[1]['mail'] != '' ? "<a href=\"mailto:" . $user_cache[1]['mail'] . "\">" . $user_cache[1]['name'] . "</a>" : $user_cache[1]['name']; ?>
    <div class="widget shadow-theme">
        <div class="widget-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li bloggerinfo">
            <div>
				<?php if (!empty($user_cache[1]['photo']['src'])): ?>
                    <img class='bloggerinfo-img' src="<?php echo BLOG_URL . $user_cache[1]['photo']['src']; ?>" alt="blogger"/>
				<?php endif; ?>
            </div>
            <p class='bloginfo-name'><b><?php echo $name; ?></b></p>
            <p class='bloginfo-cache'> <?php echo $user_cache[1]['des']; ?></p>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：日历
 */
function widget_calendar($title) { ?>
    <div class="widget shadow-theme">
        <div class="widget-title m">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li">
            <div id="calendar"></div>
            <script>sendinfo('<?php echo Calendar::url(); ?>', 'calendar');</script>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：标签
 */
function widget_tag($title) {
	global $CACHE;
	$tag_cache = $CACHE->readCache('tags'); ?>
    <div class="widget shadow-theme">
        <div class="widget-title m">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li tag-container">
			<?php foreach ($tag_cache as $value): ?>
                <span style="font-size:<?php echo $value['fontsize']; ?>pt; line-height:30px;">
            <a href="<?php echo Url::tag($value['tagurl']); ?>" title="<?php echo $value['usenum'] + 2; ?> 篇文章" class='tags-side'><?php echo $value['tagname']; ?></a></span>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：分类
 */
function widget_sort($title) {
	global $CACHE;
	$sort_cache = $CACHE->readCache('sort'); ?>
    <div class="widget shadow-theme">
        <div class="widget-title m">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li log-classify-f">
			<?php
			foreach ($sort_cache as $value):
				if ($value['pid'] != 0) continue;
				?>
                <li>
                    <a href="<?php echo Url::sort($value['sid']); ?>"><?php echo $value['sortname']; ?>&nbsp;&nbsp;(<?php echo $value['lognum'] ?>)</a>
					<?php if (!empty($value['children'])): ?>
                        <ul class="log-classify-c">
							<?php
							$children = $value['children'];
							foreach ($children as $key):
								$value = $sort_cache[$key];
								?>
                                <li>
                                    <a href="<?php echo Url::sort($value['sid']); ?>">-&nbsp;&nbsp;<?php echo $value['sortname']; ?>
                                        &nbsp;&nbsp;(<?php echo $value['lognum'] ?>)</a>
                                </li>
							<?php endforeach; ?>
                        </ul>
					<?php endif; ?>
                </li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：最新评论
 */
function widget_newcomm($title) {
	global $CACHE;
	$com_cache  = $CACHE->readCache('comment');
    $isGravatar = Option::get('isgravatar');
	?>
    <div class="widget shadow-theme">
        <div class="widget-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <hr style="margin-bottom: 4px;"/>
        <ul class="unstyle-li">
			<?php
			foreach ($com_cache as $value):
				$url = Url::comment($value['gid'], $value['page'], $value['cid']);
				?>
                <li class='comment-info' id="side-comment">
                <?php if ($isGravatar == 'y'): ?>
                    <img class='comment-info_img' src="<?php echo getGravatar($value['mail']); ?>"/>
                <?php endif; ?>
                    <span class='comm-lates-name'><?php echo $value['name']; ?></span>
                    <span class='logcom-latest-time'><?php echo smartDate($value['date']); ?></span><br/>
                    <a href="<?php echo $url; ?>" style="color: #989898;"><?php echo $value['content']; ?></a>
                    <hr>
                </li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：最新文章
 */
function widget_newlog($title) {
	global $CACHE;
	$newLogs_cache = $CACHE->readCache('newlog');
	?>
    <div class="widget shadow-theme">
        <div class="widget-title m">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li">
			<?php foreach ($newLogs_cache as $value): ?>
                <li class='blog-lates'><a href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a></li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：热门文章
 */
function widget_hotlog($title) {
	$index_hotlognum = Option::get('index_hotlognum');
	$Log_Model = new Log_Model();
	$hotLogs = $Log_Model->getHotLog($index_hotlognum); ?>
    <div class="widget shadow-theme">
        <div class="widget-title m">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li">
			<?php foreach ($hotLogs as $value): ?>
                <li><a href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a></li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：搜索
 */
function widget_search($title) { ?>
    <div class="widget shadow-theme">
        <div class="widget-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li" style="text-align: center;">
            <form name="keyform" method="get" action="<?php echo BLOG_URL; ?>index.php">
                <input name="keyword" class="search form-control" autocomplete="off" type="text"/>
                <input type="submit" value="搜索">
            </form>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：归档
 */
function widget_archive($title) {
	$bar_id = "36";
	global $CACHE;
	$record_cache = $CACHE->readCache('record');
	?>
    <div class="widget shadow-theme">
        <div class="widget-title m">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li">
			<?php foreach ($record_cache as $value): ?>
                <li><a href="<?php echo Url::record($value['date']); ?>"><?php echo $value['record']; ?>(<?php echo $value['lognum']; ?>)</a></li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 侧边栏：自定义组件
 */
function widget_custom_text($title, $content) { ?>
    <div class="widget shadow-theme">
        <div class="widget-title m">
            <h3><?php echo $title; ?></h3>
        </div>
        <ul class="unstyle-li">
			<?php echo $content; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 页顶：导航
 */
function blog_navi() {
	global $CACHE;
	$navi_cache = $CACHE->readCache('navi');
	?>
    <div class="blog-header-nav" id="navbarResponsive">
        <ul class="nav-list">
			<?php
			foreach ($navi_cache as $value):
				if ($value['pid'] != 0) {
					continue;
				}
				if ($value['url'] == ROLE_ADMIN && (ROLE == ROLE_ADMIN || ROLE == ROLE_WRITER)):
					?>
                    <li class="list-item list-menu"><a href="<?php echo BLOG_URL; ?>admin/" class="nav-link">管理</a></li>
                    <li class="list-item list-menu"><a href="<?php echo BLOG_URL; ?>admin/?action=logout" class="nav-link">退出</a></li>
					<?php
					continue;
				endif;
				$newtab = $value['newtab'] == 'y' ? 'target="_blank"' : '';
				$value['url'] = $value['isdefault'] == 'y' ? BLOG_URL . $value['url'] : trim($value['url'], '/');
				$current_tab = BLOG_URL . trim(Dispatcher::setPath(), '/') == $value['url'] ? 'active' : '';
				?>
				<?php if (!empty($value['children']) || !empty($value['childnavi'])) : ?>
                <li class="list-item list-menu">
					<?php if (!empty($value['children'])): ?>
                        <a class='nav-link has-down' id="nav_link""  <?php echo $newtab; ?>><?php echo $value['naviname']; ?> <b class="caret"></b></a>
                        <ul class="dropdown-menus">
							<?php foreach ($value['children'] as $row) {
								echo '<li class="list-item list-menu"><a class="nav-link" href="' . Url::sort($row['sid']) . '">' . $row['sortname'] . '</a></li>';
							} ?>
                        </ul>
					<?php endif; ?>
					<?php if (!empty($value['childnavi'])) : ?>
                        <a class='nav-link has-down' id="nav_link" <?php echo $newtab; ?> ><?php echo $value['naviname']; ?><b class="caret"></b></a>
                        <ul class="dropdown-menus">
							<?php foreach ($value['childnavi'] as $row) {
								$newtab = $row['newtab'] == 'y' ? 'target="_blank"' : '';
								echo '<li class="list-item list-menu"><a class="nav-link" href="' . $row['url'] . "\" $newtab >" . $row['naviname'] . '</a></li>';
							} ?>
                        </ul>
					<?php endif; ?>
                </li>
			<?php else: ?>
                <li class="list-item list-menu"><a class="nav-link" href="<?php echo $value['url']; ?>" <?php echo $newtab; ?>><?php echo $value['naviname']; ?></a></li>
			<?php endif; ?>
			<?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<?php
/**
 * 文章列出卡片：置顶标志
 */
function topflg($top, $sortop = 'n', $sortid = null) {
	$ishome_flg = '<span title="首页置顶" class="log-topflg" />';
	$issort_flg = '<span title="分类置顶" class="log-topflg" />';
	if (blog_tool_ishome()) {
		echo $top == 'y' ? $ishome_flg : '';
	} elseif ($sortid) {
		echo $sortop == 'y' ? $issort_flg : '';
	}
}

?>
<?php
/**
 * 文章查看页：编辑链接
 */
function editflg($logid, $author) {
	$editflg = ROLE == ROLE_ADMIN || $author == UID ? '<a href="' . BLOG_URL . 'admin/article.php?action=edit&gid=' . $logid . '" target="_blank">&nbsp;&nbsp;&nbsp;编辑</a>' : '';
	echo $editflg;
}

?>
<?php
/**
 * 文章列出页和文章查看页：分类
 */
function blog_sort($blogid) {
	global $CACHE;
	$log_cache_sort = $CACHE->readCache('logsort');
	?>
	<?php if (!empty($log_cache_sort[$blogid])) { ?>
        <a href="<?php echo Url::sort($log_cache_sort[$blogid]['id']); ?>"><?php echo $log_cache_sort[$blogid]['name']; ?></a>
	<?php } else { ?>
        <a href="#">无</a>
	<?php }
} ?>
<?php
/**
 * 文章列出页和文章查看页：标签
 */
function blog_tag($blogid) {
	global $CACHE;
	$tag_model = new Tag_Model();

	$log_cache_tags = $CACHE->readCache('logtags');
	if (!empty($log_cache_tags[$blogid])) {
		$tag = '';
        echo '标签：';
		foreach ($log_cache_tags[$blogid] as $value) {
			$tag .= "	<a href=\"" . Url::tag($value['tagurl']) . "\" class='tags'  title='标签' >" . $value['tagname'] . '</a>';
		}
		echo $tag;
	} else {
		$tag_ids = $tag_model->getTagIdsFromBlogId($blogid);
		$tag_names = $tag_model->getNamesFromIds($tag_ids);
		if (!empty($tag_names)) {
			$tag = '标签:';
			foreach ($tag_names as $key => $value) {
				$tag .= "	<a href=\"" . Url::tag(rawurlencode($value)) . "\" class='tags' title='标签' >" . htmlspecialchars($value) . '</a>';
			}
			echo $tag;
		}
	}
}

?>
<?php
/**
 * 文章列出页和文章查看页：作者
 */
function blog_author($uid) {
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$author = $user_cache[$uid]['name'];
	$mail = $user_cache[$uid]['mail'];
	$des = $user_cache[$uid]['des'];
	$title = !empty($mail) || !empty($des) ? "title=\"$des $mail\"" : '';
	echo '<a href="' . Url::author($uid) . "\" $title>$author</a>";
}

?>
<?php
/**
 * 文章查看页：相邻文章
 */
function neighbor_log($neighborLog) {
	extract($neighborLog); ?>
	<?php if ($prevLog): ?>
        <span class="prev-log"><a href="<?php echo Url::log($prevLog['gid']) ?>" title="<?php echo $prevLog['title']; ?>">上一篇</a></span>
	<?php endif; ?>
	<?php if ($nextLog): ?>
        <span class="next-log"><a href="<?php echo Url::log($nextLog['gid']) ?>" title="<?php echo $nextLog['title']; ?>">下一篇</a></span>
	<?php endif; ?>
<?php } ?>
<?php
/**
 * 文章查看页：评论列表
 */
function blog_comments($comments) {
	extract($comments);
	if ($commentStacks): ?>
    
        <a name="comments"></a>
        <div class="comment-header"><b>评论：</b></div>
	<?php endif; ?>
	<?php
	$isGravatar = Option::get('isgravatar');

	foreach ($commentStacks as $cid):
		$comment = $comments[$cid];
		$comment['poster'] = $comment['url'] ? '<a href="' . $comment['url'] . '" target="_blank">' . $comment['poster'] . '</a>' : $comment['poster'];
		?>
        <div class="comment" id="comment-<?php echo $comment['cid']; ?>">
            <a name="<?php echo $comment['cid']; ?>"></a>
			<?php if ($isGravatar == 'y'): ?>
                <div class="avatar"><img src="<?php echo getGravatar($comment['mail']); ?>"/></div>
            <div class="comment-infos">
                <div class="arrow"></div>
                <b><?php echo $comment['poster']; ?> </b><span class="comment-time"><?php echo $comment['date']; ?></span>
                <div class="comment-content"><?php echo $comment['content']; ?></div>
                <div class="comment-reply"><a class="com-reply">回复</a></div>
            </div>
            <?php else: ?>
            <div class="comment-infos-unGravatar">
                <b><?php echo $comment['poster']; ?> </b><span class="comment-time"><?php echo $comment['date']; ?></span>
                <div class="comment-content"><?php echo $comment['content']; ?></div>
                <div class="comment-reply"><a class="com-reply">回复</a></div>
            </div>
            <?php endif; ?>
			<?php blog_comments_children($comments, $comment['children']); ?>
        </div>
	<?php endforeach; ?>
    <div id="pagenavi">
		<?php echo $commentPageUrl; ?>
    </div>
<?php } ?>
<?php
/**
 * 文章查看页：子评论
 */
function blog_comments_children($comments, $children) {
	$isGravatar = Option::get('isgravatar');
	foreach ($children as $child):
		$comment = $comments[$child];
		$comment['poster'] = $comment['url'] ? '<a href="' . $comment['url'] . '" target="_blank">' . $comment['poster'] . '</a>' : $comment['poster'];
		?>
        <div class="comment comment-children" id="comment-<?php echo $comment['cid']; ?>">
            <a name="<?php echo $comment['cid']; ?>"></a>
			<?php if ($isGravatar == 'y'): ?>
            <div class="avatar"><img src="<?php echo getGravatar($comment['mail']); ?>"/></div>
            <div class="comment-infos">
                <div class="arrow"></div>
                <b><?php echo $comment['poster']; ?> </b><span class="comment-time"><?php echo $comment['date']; ?></span>
                <div class="comment-content"><?php echo $comment['content']; ?></div>
				<?php if ($comment['level'] < 4): ?>
                <div class="comment-reply"><a class="com-reply">回复</a>
                </div><?php endif; ?>
            </div>
            <?php else: ?>
            <div class="comment-infos-unGravatar">
                <b><?php echo $comment['poster']; ?> </b><span class="comment-time"><?php echo $comment['date']; ?></span>
                <div class="comment-content"><?php echo $comment['content']; ?></div>
                <?php if ($comment['level'] < 4): ?>
                <div class="comment-reply"><a class="com-reply">回复</a>
                </div><?php endif; ?>
            </div>
            <?php endif; ?>
			<?php blog_comments_children($comments, $comment['children']); ?>
        </div>
	<?php endforeach; ?>
<?php } ?>
<?php
/**
 * 文章查看页：评论表单
 */
function blog_comments_post($logid, $ckname, $ckmail, $ckurl, $verifyCode, $allow_remark) {
	$isNeedChinese = Option::get('comment_needchinese');

	if ($allow_remark == 'y'): ?>
        <div id="comment-place">
            <div class="comment-post" id="comment-post">
                <div class="cancel-reply" id="cancel-reply" style="display:none"><a>取消回复</a></div>
                <p class="comment-header">
                    <a name="respond"></a><br></p>
                <form class="commentform" method="post" name="commentform" action="<?php echo BLOG_URL; ?>index.php?action=addcom" id="commentform"
                      is-chinese="<?php echo $isNeedChinese; ?>">
                    <input type="hidden" name="gid" value="<?php echo $logid; ?>"/>
                    <textarea class="form-control log_comment" name="comment" id="comment" rows="10" tabindex="4" required></textarea>
					<?php if (ROLE == ROLE_VISITOR): ?>
                        <div class="comment-info" id="comment-info">
                            <input class="form-control com_control comment-name" id="info_n" autocomplete="off" type="text" name="comname" maxlength="49"
                                   value="<?php echo $ckname; ?>" size="22"
                                   tabindex="1" placeholder="昵称*" required/>
                            <input class="form-control com_control comment-mail" id="info_m" autocomplete="off" type="text" name="commail" maxlength="128"
                                   value="<?php echo $ckmail; ?>" size="22"
                                   tabindex="2" placeholder="邮件地址"/>
                            <input class="form-control com_control comment-url" id="info_u" autocomplete="off" type="text" name="comurl" maxlength="128"
                                   value="<?php echo $ckurl; ?>" size="22"
                                   tabindex="3" placeholder="个人主页"/>
                        </div>
					<?php endif; ?>

                    <span class="com_submit_p">
                        <input class="btn"<?php if ($verifyCode != "") { ?> type="button" data-toggle="modal" data-target="#myModal"<?php } else { ?> type="submit"<?php } ?>
                               id="comment_submit" value="发布评论" tabindex="6"/>
                    </span>
					<?php if ($verifyCode != "") { ?>
                        <!-- 验证窗口 -->
                        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content" style="display: table-cell;">
                                    <div class="modal-header" style="border-bottom: 0px;">
                                        输入验证码
                                    </div>
									<?php echo $verifyCode; ?>
                                    <div class="modal-footer" style="border-top: 0px;">
                                        <button type="button" class="btn" id="close-modal" data-dismiss="modal">关闭</button>
                                        <button type="submit" class="btn" id="comment_submit2">提交</button>
                                    </div>
                                </div>
                            </div>
                            <div class="lock-screen"></div>
                        </div>
                        <!-- 验证窗口(end) -->
					<?php } ?>
                    <input type="hidden" name="pid" id="comment-pid" value="0" size="22" tabindex="1"/>
                </form>
            </div>
        </div>
	<?php endif; ?>
<?php } ?>
<?php
/**
 * 判断函数：是否是首页
 */
function blog_tool_ishome() {
	if (BLOG_URL . trim(Dispatcher::setPath(), '/') == BLOG_URL) {
		return true;
	} else {
		return FALSE;
	}
}
?>
