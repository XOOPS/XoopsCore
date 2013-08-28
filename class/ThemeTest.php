<?php
require_once(dirname(__FILE__).'/../init.php');

class ThemeTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsTheme';
	
    public function SetUp() {
    }
    
    public function test_100() {
		$theme = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $theme);
        $this->assertSame(true, $theme->renderBanner);
        $this->assertSame('', $theme->folderName);
        $this->assertSame('', $theme->path);
        $this->assertSame('', $theme->url);
        $this->assertSame(true, $theme->bufferOutput);
        $this->assertSame('theme.html', $theme->canvasTemplate);
        $this->assertSame('themes', $theme->themesPath);
        $this->assertSame('', $theme->contentTemplate);
        $this->assertSame(0, $theme->contentCacheLifetime);
        $this->assertSame(null, $theme->contentCacheId);
        $this->assertSame('', $theme->content);
        $this->assertSame(array('XoopsThemeBlocksPlugin'), $theme->plugins);
        $this->assertSame(0, $theme->renderCount);
        $this->assertSame(false, $theme->template);
        $this->assertSame(array(), $theme->metas['meta']);
        $this->assertSame(array(), $theme->metas['link']);
        $this->assertSame(array(), $theme->metas['script']);
        $this->assertSame(array(), $theme->htmlHeadStrings);
        $this->assertSame(array(), $theme->templateVars);
        $this->assertSame(true, $theme->use_extra_cache_id);
        $this->assertSame('file', $theme->headersCacheEngine);
    }
}
