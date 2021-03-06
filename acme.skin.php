<? 
if ( ! defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}//end if

class SkinAcme extends SkinTemplate {
	/** Using Bootstrap */
	public $skinname = 'acme';
	public $stylename = 'acme';
	public $template = 'AcmeTemplate';
	public $useHeadElement = true;

	/**
	 * initialize the page
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$out->addModuleScripts( 'skins.acme' );
		
		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' );
	}//end initPage

	/**
	 * prepares the skin's CSS
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		global $wgSiteCSS;

		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( 'skins.acme' );
		
		$out->addStyle( 'acme/font-awesome/css/font-awesome.min.css' );

	}//end setupSkinUserCss
}

class AcmeTemplate extends BaseTemplate {
	
	public $skin;

	public function execute() {
		global $wgRequest, $wgUser, $wgSitename, $wgSitenameshort, $wgCopyrightLink, $wgCopyright, $wgBootstrap, $wgArticlePath, $wgGoogleAnalyticsID, $wgSiteCSS;
		global $wgEnableUploads;
		global $wgLogo;
		global $wgTOCLocation;
		global $wgNavBarClasses;
		global $wgSubnavBarClasses;

		$this->skin = $this->data['skin'];
		$_TITLE = $this->getSkin()->getRelevantTitle();
		$action = $wgRequest->getText( 'action' );
		$url_prefix = str_replace( '$1', '', $wgArticlePath );
		$revid = $this->getSkin()->getRequest()->getText( 'oldid' );
		$_URITITLE = rawurlencode($_TITLE);

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();
		$this->html('headelement');
		?>
		<!--header start-->
    <header class="head-section">
      <div class="navbar navbar-default navbar-static-top container">
          <div class="navbar-header">
              <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse"
              type="button"><span class="icon-bar"></span> <span class="icon-bar"></span>
              <span class="icon-bar"></span></button> <a class="navbar-brand" href="<? echo $this->data['nav_urls']['mainpage']['href']; ?>"><img src='/test/skins/acme/img/logo.png' width='200px'></a>
          </div>

          <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
				<li><? echo Linker::linkKnown( SpecialPage::getTitleFor( 'RecentChanges', null ), '최근 바뀐 문서'); ?></li>
				
				<li><? echo Linker::linkKnown( SpecialPage::getTitleFor( 'Random', null ), '랜덤'); ?></li>
				<? $theMsg = 'toolbox';
				$theData = array_reverse($this->getToolbox()); ?>
				<li class="dropdown">
                   <a class="dropdown-toggle" data-close-others="false" data-delay="0" data-hover=
                      "dropdown" data-toggle="dropdown" href="#">도구 <i class="fa fa-angle-down"></i>
                      </a>
                      <ul aria-labelledby="<? echo $this->msg($theMsg); ?>" role="menu" class="dropdown-menu" <? $this->html( 'userlangattributes' ); ?>>
						<?
							foreach( $theData as $key => $item ) {
								if (preg_match('/specialpages|whatlinkshere/', $key)) {
									continue;
								}
								echo $this->makeListItem( $key, $item );
							}
						?>
<li id="t-re"><? echo '<a href="/test/index.php?title=특수:가리키는문서/'.$_URITITLE.'">';?>역링크</a></li>
						<li id="t-Special"><? echo Linker::linkKnown( SpecialPage::getTitleFor( '특수문서', null ), '특수문서', array( 'title' => '특수문서' ) ); ?></li>
						
						</ul>
				</li>
				
				<? if ($wgUser->isLoggedIn()) {
				
				function loginBox() {
					global $wgUser, $wgRequest;
				}
				
					if ($wgUser->isLoggedIn()) {
							if ($wgUser->getEmailAuthenticationTimestamp()) {
							$email = trim($wgUser->getEmail());
							$email = strtolower($email);
							$email = md5($email) . "?d=identicon";
						}
						else {
							$result = mt_rand(1, 10000);
							$email = $result."?d=identicon&f=y";
						}
					}
				
            ?>
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" type="button" id="login-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img style='width: 32px;' class="profile-img" src="//secure.gravatar.com/avatar/<? echo $email; ?>" /></a>
					<ul class="dropdown-menu">
						<li id="pt-mypage"><? echo Linker::linkKnown( Title::makeTitle( NS_USER, $wgUser->getName() ), $wgUser->getName(), array( 'title' => '사용자 문서를 보여줍니다.' ) ); ?></li>
						<li id="pt-preferences"><? echo Linker::linkKnown( SpecialPage::getTitleFor( 'preferences', null ), '환경설정', array( 'title' => '환경설정을 불러옵니다.' ) ); ?></li>
						<li id="pt-watchlist"><? echo Linker::linkKnown( SpecialPage::getTitleFor( 'watchlist', null ), '주시 문서', array( 'title' => '주시문서를 불러옵니다.') ); ?></li>
						<li id="pt-mycontris"><? echo Linker::linkKnown( SpecialPage::getTitleFor( 'Contributions', $wgUser->getName() ), '기여 문서', array( 'title' => '내 기여 목록을 불러옵니다.' ) ); ?></li>
						<li id="pt-logout"><? echo Linker::linkKnown( SpecialPage::getTitleFor( 'logout', null ), '로그아웃', array( 'title' => '위키에서 로그아웃 합니다.' ) ); ?></li>
					</ul>
				</li>
				
				<? } else {
					$result = mt_rand(1, 10000);
					$email = $result."?d=identicon&f=y";
				?>
				
				<li id="pt-login">
				<? echo Linker::linkKnown( SpecialPage::getTitleFor( 'Userlogin' ), '<img style="width: 32px;" class="profile-img" src="//secure.gravatar.com/avatar/'.$email.'" /></a>' ); ?>
				</li>
				
				<? } ?>
				
				<li>
					<form action="<? $this->text( 'wgScript' ) ?>" id="searchform" role="search">
						<input style="display: block;" class="form-control search" type="search" name="search" placeholder="Search" title=" Search <? echo $wgSitename; ?> [ctrl-option-f]" accesskey="f" id="searchInput" autocomplete="off">
						<input type="hidden" name="title" value="특수:검색">
					</form>				
				</li>
				
              </ul>
          </div>
      </div>
    </header>
    <!--header end-->
	
	<!--breadcrumbs start-->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                    <h1><? $this->html( 'title' ) ?></h1><? $this->html( 'subtitle' ) ?></span>
                </div>
                <div class="col-lg-8 col-sm-8">
                    <ol class="breadcrumb pull-right">
					<? if ( count( $this->data['content_actions']) > 0 ) {
							$namu = 1;
							foreach($this->data['content_actions'] as $pages) {
								echo '<li id="dis del-'.$namu.'"><a href="'.$pages['href'].'">'.$pages['text'].'</a></li>';
								$namu = $namu + 1;
							}
                                echo '<li id="dis del-t"><a href="/dis/index.php/questions">토론</a></li>';
							} ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->
	<!--container start-->
    <section id="body">
	
	<div class="container">
	
	<div class="row">
	<div class="col-md-10 col-md-offset-1 mar-b-30">
	<!-- 위실광고1 -->
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<ins id="noadsense" class="adsbygoogle" style="display:block" data-ad-client="ca-pub-9592402831871199" data-ad-slot="7142234264" data-ad-format="auto"></ins><br>
	<script type="text/javascript">(adsbygoogle = window.adsbygoogle || []).push({});</script>
	<!-- 광고 끝 -->
	<?	if ( $this->data['catlinks'] ) {
	$this->html( 'catlinks' );
    } ?><br>
	<? $this->html( 'bodytext' );
	if ( $this->data['dataAfterContent'] ): ?>
				<div class="data-after-content">
				<!-- dataAfterContent -->
				<? $this->html( 'dataAfterContent' ); ?>
				<!-- /dataAfterContent -->
				</div>
	<? endif; ?>
	</div>
	</div>
	</div>
	</section>
	<div class="scroll-buttons"><a class="random-link" href="/test/index.php?title=%ED%8A%B9%EC%88%98:%EC%9E%84%EC%9D%98%EB%AC%B8%EC%84%9C"><i class="fa fa-exchange" aria-hidden="true"></i>
<span style="display:none">Random</span></a><a class="scroll-button" href="<? echo '/test/index.php?title='.$_URITITLE.'&oldid='.$revid.'&action=edit'; ?>"><i class="fa fa-pencil" aria-hidden="true"></i>
</a><a class="scroll-toc" href="#toc"><i class="fa fa-list-alt" aria-hidden="true"></i>
</a><a class="scroll-button" href="#"><i class="fa fa-arrow-up" aria-hidden="true"></i>
</a><a class="scroll-bottom" href="#footer"><i class="fa fa-arrow-down" aria-hidden="true"></i>
</a></div>
	<!--small footer start -->
    <footer class="footer-small" id="footer">
        <div class="container">
            <div class="row">
                  <div class="copyright">
                    <p>내용은 별도로 명시하지 않을 경우 <a style='color:#48cfad;' href='http://creativecommons.org/publicdomain/zero/1.0/deed.ko'>퍼블릭 도메인</a>에 따라 사용할 수 있습니다.</p>
                  </div>
            </div>
        </div>
    </footer>
     <!--small footer end-->
	<?
		$this->html('bottomscripts');
		$this->html('reporttime');

		if ( $this->data['debug'] ) {
			$this->text( 'debug' );
		}
		?>
	</body>
		</html>
	<? }
	
} ?>