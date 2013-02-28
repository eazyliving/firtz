(function($) {
	'use strict';

	var startAtTime = false,
		stopAtTime = false,
		// Keep all Players on site
		players = [],
		// Timecode as described in http://podlove.org/deep-link/
		// and http://www.w3.org/TR/media-frags/#fragment-dimensions
		timecodeRegExp = /(?:(\d+):)?(\d+):(\d+)(\.\d+)?([,-](?:(\d+):)?(\d+):(\d+)(\.\d+)?)?/,
		wrapperDummy = $('
			<div class="podlovewebplayer_meta">
				<a class="bigplay" href="#">Play Episode</a>
				<div class="coverart"><img src="samples/coverimage.png" alt=""></div>
				<h3 class="episodetitle">
					<a href="http://podlove.github.com/podlove-web-player/standalone.html">{TITLE}</a>
				</h3>
				<div class="subtitle">{SUBTITLE}</div>
				<div class="togglers">
					<a href="#" class="infowindow infobuttons icon-info-sign" title="{MOREINFORMATION}"></a>
					<a href="#" class="chaptertoggle infobuttons icon-list-ul" title="show/hide chapters"></a>
					<a href="#" class="showcontrols infobuttons icon-time" title="show/hide controls box"></a>
					<a href="#" class="showsharebuttons infobuttons icon-share" title="show/hide share buttons"></a>
				</div>
			</div>
			<div style="height: 0px;" class="summary">{SUMMARY}</div>
			<div style="width: 100%; height: 30px;" id="mep_0" class="mejs-container svg mejs-audio">
		<div class="mejs-inner">
		<div class="mejs-mediaelement"><audio src="samples/podlove-test-track.ogg" id="testplayer">
			<source src="samples/podlove-test-track.mp4" type="audio/mp4">
			<source src="samples/podlove-test-track.mp3" type="audio/mp3">
			<source src="samples/podlove-test-track.ogg" type="audio/ogg; codecs=vorbis">
			<source src="samples/podlove-test-track.opus" type="audio/ogg; codecs=opus">
		</audio></div>
		<div class="mejs-layers">
			<div style="display: none; width: 100%; height: 30px;" class="mejs-poster mejs-layer"></div></div>
			<div class="mejs-controls">
				<div class="mejs-time mejs-currenttime-container">
					<span class="mejs-currenttime">00:00:00</span>
				</div>
			<div style="width: 1038px;" class="mejs-time-rail">
				<span style="width: 1028px;" class="mejs-time-total">
					<span style="display: none;" class="mejs-time-buffering"></span>
					<span style="width: 1028px;" class="mejs-time-loaded"></span>
					<span style="width: 0px;" class="mejs-time-current"></span>
					<span style="left: -7px;" class="mejs-time-handle"></span>
					<span style="display: none; left: 146px;" class="mejs-time-float">
						<span class="mejs-time-float-current">00:00</span>
						<span class="mejs-time-float-corner"></span>
					</span>
				</span>
			</div>
			<div class="mejs-time mejs-duration-container"><span class="mejs-duration">00:00:02</span></div><div class="mejs-button mejs-volume-button mejs-mute"><button type="button" aria-controls="mep_0" title="Mute Toggle"></button></div><div class="mejs-horizontal-volume-slider mejs-mute"><div class="mejs-horizontal-volume-total"></div><div style="width: 40px;" class="mejs-horizontal-volume-current"></div><div style="left: 27px;" class="mejs-horizontal-volume-handle"></div></div></div>
			<div class="mejs-clear"></div></div></div><div class="podlovewebplayer_timecontrol podlovewebplayer_controlbox"><a href="#" class="prevbutton infobuttons icon-step-backward" title="previous chapter"></a><a href="#" class="nextbutton infobuttons icon-step-forward" title="next chapter"></a><a href="#" class="rewindbutton infobuttons icon-backward" title="Rewind 30 seconds"></a><a href="#" class="forwardbutton infobuttons icon-forward" title="Skip 30 seconds"></a></div><div class="podlovewebplayer_sharebuttons podlovewebplayer_controlbox"><a href="#" class="currentbutton infobuttons icon-link" title="get current position link"></a><a href="#" target="_blank" class="tweetbutton infobuttons icon-twitter" title="tweet current position"></a><a href="#" target="_blank" class="fbsharebutton infobuttons icon-facebook" title="share current position on facebook"></a><a href="#" target="_blank" class="gplusbutton infobuttons icon-google-plus" title="share current position on Google+"></a><a href="#" target="_blank" class="mailbutton infobuttons icon-envelope" title="share current position via mail"></a></div><div class="podlovewebplayer_chapterbox showonplay active"><table style="display: table;" class="podlovewebplayer_chapters linked linked_all"><caption>Podcast Chapters</caption><thead><tr><th scope="col">Chapter Number</th><th scope="col">Start time</th><th scope="col">Title</th><th scope="col">Duration</th></tr></thead><tbody><tr class="chaptertr" data-start="0" data-end="1"><td class="starttime"><span>00:00</span></td><td>Chapter One title</td><td class="timecode">
<span>00:01</span>
</td>
</tr><tr class="chaptertr oddchapter" data-start="1" data-end="1.5"><td class="starttime"><span>00:01</span></td><td>Chapter Two with <a href="#">hyperlink</a></td><td class="timecode">
<span>00:01</span>
</td>
</tr><tr class="chaptertr" data-start="1.5" data-end="2.5"><td class="starttime"><span>00:02</span></td><td>Chapter Three</td><td class="timecode">
<span>00:01</span>
</td>
</tr></tbody></table></div><div class="podlovewebplayer_tableend"></div>
		');

	var methods = {
		init: function(options) {
			// MEJS options default values
			var mejsoptions = {
				defaultVideoWidth: 480,
				defaultVideoHeight: 270,
				videoWidth: -1,
				videoHeight: -1,
				audioWidth: -1,
				audioHeight: 30,
				startVolume: 0.8,
				loop: false,
				enableAutosize: true,
				features: ['playpause','current','progress','duration','tracks','volume','fullscreen'],
				alwaysShowControls: false,
				iPadUseNativeControls: false,
				iPhoneUseNativeControls: false,
				AndroidUseNativeControls: false,
				alwaysShowHours: false,
				showTimecodeFrameCount: false,
				framesPerSecond: 25,
				enableKeyboard: true,
				pauseOtherPlayers: true,
				duration: false
			};

			// Additional parameters default values
			var params = $.extend({}, {
				chapterlinks: 'all',
				width: '100%',
				duration: false,
				chaptersVisible: false,
				timecontrolsVisible: false,
				sharebuttonsVisible: false,
				summaryVisible: false
			}, options);

			// turn each player in the current set into a Podlove Web Player
			return this.map(function(index, player){

				var richplayer = false,
					haschapters = false;

				//fine tuning params
				if (params.width.toLowerCase() == 'auto') {
					params.width = '100%';
				} else {
					params.width = params.width.replace('px', '');
				}

				//audio params
				if (player.tagName == 'AUDIO') {
					if (typeof params.audioWidth !== 'undefined') {
						params.width = params.audioWidth;
					}
					mejsoptions.audioWidth = params.width;
					
					//kill fullscreen button
					$.each(mejsoptions.features, function(i){
						if (this == 'fullscreen') {
							mejsoptions.features.splice(i, 1);
						}
					});

				//video params
				} else if (player.tagName == 'VIDEO') {

					if (typeof params.height !== 'undefined') {
						mejsoptions.videoWidth = params.width;
						mejsoptions.videoHeight = params.height;
					}

					if (typeof $(player).attr('width') !== 'undefined') {
						params.width = $(player).attr('width');
					}
				}

				//duration can be given in seconds or in timecode format
				if (params.duration && params.duration != parseInt( params.duration, 10)) {
					var secArray = parseTimecode(params.duration);
					params.duration = secArray[0];
				}
				
				//Overwrite MEJS default values with actual data
				$.each(mejsoptions, function(key, value){
					if (typeof params[key] !== 'undefined') {
						mejsoptions[key] = params[key];
					}
				});

				//wrapper and init stuff
				if (params.width == parseInt( params.width, 10)) {
					params.width += 'px';
				}

				var orig = player;

				player = $(player).clone().wrap('<div class="podlovewebplayer_wrapper" style="width: ' + params.width + '"></div>')[0];
				var deepLink,
					wrapper = $(player).parent();

				players.push(player);

				//add params from html fallback area
				$(player).find('[data-pwp]').each(function(){
					params[$(this).data('pwp')] = $(this).html();
					$(this).remove();
				});

				//build rich player with meta data
				if ( typeof params.chapters !== 'undefined' ||
						typeof params.title !== 'undefined' ||
						typeof params.subtitle !== 'undefined' ||
						typeof params.summary !== 'undefined' ||
						typeof params.poster !== 'undefined' ||
						typeof $(player).attr('poster') !== 'undefined'
						) {

					//set status variable
					var richplayer = true;
					
					wrapper.addClass('podlovewebplayer_' + player.tagName.toLowerCase());

					if(player.tagName == "AUDIO") {
						
						//kill play/pause button from miniplayer
						$.each(mejsoptions.features, function(i){
							if (this == 'playpause') {
								mejsoptions.features.splice(i,1);
							}
						});
						
						wrapper.prepend('<div class="podlovewebplayer_meta"></div>');
						
						wrapper.find('.podlovewebplayer_meta').prepend('<a class="bigplay" href="#">Play Episode</a>');
						if (typeof params.poster !== 'undefined') {
							wrapper.find('.podlovewebplayer_meta').append(
								'<div class="coverart"><img src="'+params.poster+'" alt=""></div>');
						}
						if (typeof $(player).attr('poster') !== 'undefined') {
							wrapper.find('.podlovewebplayer_meta').append(
								'<div class="coverart"><img src="'+$(player).attr('poster')+'" alt=""></div>');
						}
					}

					if (player.tagName == "VIDEO") {
						wrapper.prepend('<div class="podlovewebplayer_top"></div>');
						wrapper.append('<div class="podlovewebplayer_meta"></div>');
					}
					
					if (typeof params.title !== 'undefined') {
						if (typeof params.permalink !== 'undefined') {
							wrapper.find('.podlovewebplayer_meta').append(
								'<h3 class="episodetitle"><a href="'+params.permalink+'">'+params.title+'</a></h3>');
						} else {
							wrapper.find('.podlovewebplayer_meta').append(
								'<h3 class="episodetitle">'+params.title+'</h3>');
						}
					}
					if (typeof params.subtitle !== 'undefined') {
						wrapper.find('.podlovewebplayer_meta').append(
							'<div class="subtitle">'+params.subtitle+'</div>');
					}

					//always render toggler buttons wrapper
					wrapper.find('.podlovewebplayer_meta').append('<div class="togglers"></div>');
					
					if (typeof params.summary !== 'undefined') {
						var summaryActive = "";
						if (params.summaryVisible == true) {
							summaryActive = " active";
						}
						wrapper.find('.togglers').append(
							'<a href="#" class="infowindow infobuttons icon-info-sign" title="more information on the episode"></a>');
						wrapper.find('.podlovewebplayer_meta').after(
							'<div class="summary'+summaryActive+'">'+params.summary+'</div>');
					}
					if (typeof params.chapters !== 'undefined') {
						wrapper.find('.togglers').append(
							'<a href="#" class="chaptertoggle infobuttons icon-list-ul" title="show/hide chapters"></a>');
					}
					wrapper.find('.togglers').append('<a href="#" class="showcontrols infobuttons icon-time" title="show/hide controls box"></a>');
				}

				var timecontrolsActive = "";
				if (params.timecontrolsVisible == true) {
					timecontrolsActive = " active";
				}
				var sharebuttonsActive = "";
				if (params.sharebuttonsVisible == true) {
					sharebuttonsActive = " active";
				}
				wrapper.append('<div class="podlovewebplayer_timecontrol podlovewebplayer_controlbox'+timecontrolsActive+'"></div>');
				
				if (typeof params.chapters !== 'undefined') {
					wrapper.find('.podlovewebplayer_timecontrol').append('<a href="#" class="prevbutton infobuttons icon-step-backward" title="previous chapter"></a><a href="#" class="nextbutton infobuttons icon-step-forward" title="next chapter"></a>')
					wrapper.find('.controlbox').append('<a href="#" class="prevbutton infobuttons icon-step-backward" title="previous chapter"></a><a href="#" class="nextbutton infobuttons icon-step-forward" title="next chapter"></a>');
				}
				wrapper.find('.podlovewebplayer_timecontrol').append(
					'<a href="#" class="rewindbutton infobuttons icon-backward" title="Rewind 30 seconds"></a>');
				wrapper.find('.podlovewebplayer_timecontrol').append('<a href="#" class="forwardbutton infobuttons icon-forward" title="Skip 30 seconds"></a>');
				if (typeof wrapper.closest('.podlovewebplayer_wrapper').find('.episodetitle a').attr('href') !== 'undefined') {
					wrapper.append('<div class="podlovewebplayer_sharebuttons podlovewebplayer_controlbox'+sharebuttonsActive+'"></div>');
					wrapper.find('.togglers').append('<a href="#" class="showsharebuttons infobuttons icon-share" title="show/hide share buttons"></a>');
					wrapper.find('.podlovewebplayer_sharebuttons').append('<a href="#" class="currentbutton infobuttons icon-link" title="get current position link"></a>');
					wrapper.find('.podlovewebplayer_sharebuttons').append('<a href="#" target="_blank" class="tweetbutton infobuttons icon-twitter" title="tweet current position"></a>');
					wrapper.find('.podlovewebplayer_sharebuttons').append('<a href="#" target="_blank" class="fbsharebutton infobuttons icon-facebook" title="share current position on facebook"></a>');
					wrapper.find('.podlovewebplayer_sharebuttons').append('<a href="#" target="_blank" class="gplusbutton infobuttons icon-google-plus" title="share current position on Google+"></a>');
					wrapper.find('.podlovewebplayer_sharebuttons').append('<a href="#" target="_blank" class="mailbutton infobuttons icon-envelope" title="share current position via mail"></a>');
				}

				//build chapter table
				if (typeof params.chapters !== 'undefined') {
					haschapters = true;

					generateChapterTable(params).appendTo(wrapper);
				}

				if (richplayer || haschapters) {
					wrapper.append('<div class="podlovewebplayer_tableend"></div>');
				}

				// parse deeplink
				deepLink = parseTimecode(location.href);
				if (deepLink !== false && players.length === 1) {
					$(player).attr({preload: 'auto', autoplay: 'autoplay'});
					startAtTime = deepLink[0];
					stopAtTime = deepLink[1];
				}

				// init MEJS to player
				mejsoptions.success = function (player) {
					$(wrapper).data('player', $(player));
					addBehavior(player, params);
					if (deepLink !== false && players.length === 1) {
						$('html, body').delay(150).animate({
							scrollTop: $('.podlovewebplayer_wrapper:first').offset().top - 25
						});
					}
				};

				$(orig).replaceWith(wrapper);
				$(player).mediaelementplayer(mejsoptions);
$(wrapper).data('player', $(player));

				return wrapper;
			});
		},

		toggleHeight: function () {
			return this.toggleClass('active').height(function(){
				return $(this).hasClass('active') ? $(this).data('height') + 'px' : '0px';
			});
		},

		/**
		 * Starts a player.
		 * @param time (optional)
		 */
		play: function ( time){
			if( $.isFunction(time)){
				time = time.call( this, this[0].currentTime || 0);
			}

			if(!time && ((typeof this[0].currentTime !== 'number')||(this[0].currentTime <= 0))) {
				time = 0;
			}

			/* if deeplink, set url */
			if( players.length === 1){
				setFragmentURL('t=' + generateTimecode([time]));
			}

			if( this.data('canplay')){
				if( typeof time === 'undefined'){
					this[0].play();
				} else {
					this[0].setCurrentTime(time);
					this[0].play();
				}
			} else {
				this.one('canplay', function(){
					$(this).data( 'canplay', true).podlovewebplayer( 'play', time);
				});
			}

			return this;
		},

		pause: function(){
			this[0].pause();
			return this;
		}

	};

	// this is the actual plugin
	$.fn.podlovewebplayer = function( method){
		if( method in methods){
			return methods[method].apply(this, [].slice.call(arguments,1));
		} else if( typeof method === 'object' ){
			return methods.init.apply( this, [].slice.call(arguments,0));
		}

		return this;
	};

	/**
	 * Given a list of chapters, this function creates the chapter table for the player.
	 */
	var generateChapterTable = function generateChapterTable( params){
		
		// cache the templates and clone them later on
		if( !generateChapterTable.div){
			generateChapterTable.div = $(
			'<div class="podlovewebplayer_chapterbox showonplay"><table>'
			+ '<caption>Podcast Chapters</caption><thead><tr>'
			+ '<th scope="col">Chapter Number</th>'
			+ '<th scope="col">Start time</th>'
			+ '<th scope="col">Title</th>'
			+ '<th scope="col">Duration</th>'
			+ '</tr></thead>'
			+ '<tbody></tbody></table></div>');
			
			//this is a "template" for each chapter row
			generateChapterTable.rowDummy = $(
			'<tr class="chaptertr" data-start="" data-end="">'
			+ '<td class="starttime"><span></span></td>'
			+ '<td></td>'
			+ '<td class="timecode">\n'
			+ '<span></span>\n'
			+ '</td>\n'
			+ '</tr>');

			//attach events
			generateChapterTable.div.on( 'click', '.chaptertr', function(event){
				event.preventDefault();

				if ( !( $(event.delegateTarget).find('table').hasClass('linked_all') || $(this).hasClass('loaded')))
					return;

				var startTime = $(this).data('start'),
					player = $(this).closest('.podlovewebplayer_wrapper').data('player');
console.log(player)
				player.podlovewebplayer('play', startTime);
			});
		}
		
		var div = generateChapterTable.div.clone(true),
			rowDummy = generateChapterTable.rowDummy,
			table = div.children('table'),
			tbody = table.children('tbody');

		if (params.chaptersVisible === true) {
			div.addClass('active');
		}

		table.addClass('podlovewebplayer_chapters');
		if (params.chapterlinks != 'false') {
			table.addClass('linked linked_'+params.chapterlinks);
		}


		//prepare row data
		var tempchapters = [];
		var maxchapterlength = 0;
		var maxchapterstart  = 0;

		//first round: kill empty rows and build structured object
		$.each(params.chapters.split("\n"), function(i, chapter){

			//exit early if this line contains nothing but whitespace
			if( !/\S/.test(chapter)) return;

			//extract the timestamp
			var line = $.trim(chapter);
			var tc = parseTimecode(line.substring(0,line.indexOf(' ')));
			var chaptitle = $.trim(line.substring(line.indexOf(' ')));
			tempchapters.push({start: tc[0], title: chaptitle });
		});

		//second round: collect more information
		$.each(tempchapters, function(i){
			var next = tempchapters[i+1];

			// exit early if this is the final chapter
			if( !next) return;
			
			// we need this data for proper formatting
			this.end = next.start;
			if(Math.round(this.end-this.start) > maxchapterlength) {
				maxchapterlength = Math.round(this.end-this.start);
				maxchapterstart = Math.round(next.start);
			}
		});

		//third round: build actual dom table
		$.each(tempchapters, function(i){
			var finalchapter = !tempchapters[i+1],
				duration = Math.round(this.end-this.start),
				forceHours = (maxchapterlength >= 3600),
				row = rowDummy.clone();

			//make sure the duration for all chapters are equally formatted
			if (!finalchapter) {
				this.duration = generateTimecode([duration], forceHours);
			} else {
				if (params.duration == 0) {
					this.end = 9999999999;
					this.duration = '…';
				} else {
					this.end = params.duration;
					this.duration = generateTimecode([Math.round(this.end-this.start)], forceHours);
				}
			}


			if(i % 2) {
				row.addClass('oddchapter');
			}

			//deeplink, start and end
			row.attr({
				'data-start': this.start,
				'data-end' : this.end
			});

			//if there is a chapter that starts after an hour, force '00:' on all previous chapters
			forceHours = (maxchapterstart >= 3600);

			//insert the chapter data
			row.find('.starttime > span').text( generateTimecode([Math.round(this.start)], forceHours));
			row.children('td').eq(1).html(this.title);
			row.find('.timecode > span').text( this.duration);

			row.appendTo( tbody);
		});

		// chapters list
		table.show()

		return div;
	};


	/**
	 * add chapter behavior and deeplinking: skip to referenced
	 * time position & write current time into address
	 * @param player object
	 */
	var addBehavior = function(player, params) {

		var jqPlayer = $(player),
			layoutedPlayer = jqPlayer,
			list = jqPlayer.closest('.podlovewebplayer_wrapper').find('table'),
			marks = list.find('tr'),
			canplay = false;
			
		if (players.length === 1) {
			// check if deeplink is set
			checkCurrentURL();
		}

		// get things straight for flash fallback
		if (player.pluginType == 'flash') {
			//TODO: this line is not reliable: the player might have not id
			layoutedPlayer = $('#mep_' + player.id.substring(9));
		}

		// cache some jQ objects
		var wrapper = layoutedPlayer.closest('.podlovewebplayer_wrapper'),
			metainfo = wrapper.find('.podlovewebplayer_meta'),
			summary = wrapper.find('.summary'),
			podlovewebplayer_timecontrol = wrapper.find('.podlovewebplayer_timecontrol'),
			podlovewebplayer_sharebuttons = wrapper.find('.podlovewebplayer_sharebuttons'),
			chapterdiv = wrapper.find('.podlovewebplayer_chapterbox');

		// fix height of summary for better toggability
		summary.each(function() {
			$(this).data('height', $(this).height());
			if (!$(this).hasClass('active')) {
				$(this).height('0px');
			}
		});

		chapterdiv.each(function() {
			$(this).data('height', $(this).find('.podlovewebplayer_chapters').height());
			if (!$(this).hasClass('active')) {
				$(this).height('0px');
			}
		});
		
		/**
		 * TODO: warum sollte metainfo jemals != 1 sein? Video?
		 */
		if (metainfo.length === 1) {

			metainfo.find('a.infowindow').click({player:player, summary: summary}, eventHandler.clickInfowindow);

			metainfo.find('a.showcontrols').on('click', function(){
				podlovewebplayer_timecontrol.toggleClass('active');
				podlovewebplayer_sharebuttons.removeClass('active');
				return false;
			});

			metainfo.find('a.showsharebuttons').on('click', function(){
				podlovewebplayer_sharebuttons.toggleClass('active');
				podlovewebplayer_timecontrol.removeClass('active');
				return false;
			});

			metainfo.find('.bigplay').on('click', function(){
				if($(this).hasClass('bigplay')) {
					if((typeof player.currentTime === 'number')&&(player.currentTime > 0)) {
						if (player.paused) {
							player.play();
							$(this).parent().find('.bigplay').addClass('playing');
						} else {
							player.pause();
							$(this).parent().find('.bigplay').removeClass('playing');
						}
					} else {
						player.play();
					}
				}
				return false;
			});

			//TODO: dry this up
			//wrapper.find('.chaptertoggle').click(function() {
			wrapper.find('.chaptertoggle').unbind('click').click(function(){
				wrapper.find('.podlovewebplayer_chapterbox').podloveweplayer('toggleHeight');
				return false;
			});


			wrapper.find('.prevbutton').click(function(){
				if ((typeof player.currentTime === 'number')&&(player.currentTime > 0)) {
					if(player.currentTime > chapterdiv.find('.active').data('start')+10) {
						player.setCurrentTime(chapterdiv.find('.active').data('start'));
					} else {
						player.setCurrentTime(chapterdiv.find('.active').prev().data('start'));
					}
				} else {
					player.play();
				}
				return false;
			});

			wrapper.find('.nextbutton').click(function(){
				if ((typeof player.currentTime === 'number') && (player.currentTime > 0)) {
					player.setCurrentTime(chapterdiv.find('.active').next().data('start'));
				} else {
					player.play();
				}
				return false;
			});

			wrapper.find('.rewindbutton').click(function(){
				$(player).podlovewebplayer( 'play', function(oldTime){
					return oldTime - 30;
				});
			});

			wrapper.find('.forwardbutton').click(function(){
				$(player).podlovewebplayer( 'play', function(oldTime){
					return oldTime + 30;
				});
			});

			wrapper.find('.currentbutton').click(function(){
				window.prompt('This URL directly points to the current playback position', $(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').attr('href'));
				return false;
			});

			wrapper.find('.tweetbutton').click(function(){
				window.open('https://twitter.com/share?text='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').text())+'&url='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').attr('href')), 'tweet it', 'width=550,height=420,resizable=yes');
				return false;
			});

			wrapper.find('.fbsharebutton').click(function(){
				window.open('http://www.facebook.com/share.php?t='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').text())+'&u='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').attr('href')), 'share it', 'width=550,height=340,resizable=yes');
				return false;
			});

			wrapper.find('.gplusbutton').click(function(){
				window.open('https://plus.google.com/share?title='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').text())+'&url='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').attr('href')), 'plus it', 'width=550,height=420,resizable=yes');
				return false;
			});

			wrapper.find('.adnbutton').click(function(){
				window.open('https://alpha.app.net/intent/post?text='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').text())+'%20'+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').attr('href')), 'plus it', 'width=550,height=420,resizable=yes');
				return false;
			});

			wrapper.find('.mailbutton').click(function(){
				window.location = 'mailto:?subject='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').text())+'&body='+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').text())+'%20<'+encodeURI($(this).closest('.podlovewebplayer_wrapper').find('.episodetitle a').attr('href'))+'>';
				return false;
			});
		}
		
		// wait for the player or you'll get DOM EXCEPTIONS
		jqPlayer.bind('canplay', function () {
			canplay = true;
			$(this).data('canplay',true);

			// add duration of final chapter
			if (player.duration) {
				marks.find('.timecode code').eq(-1).each(function(){
					var start = Math.floor($(this).closest('tr').data('start'));
					var end = Math.floor(player.duration);
					$(this).text(generateTimecode([end-start]));
				});
			}

			// add Deeplink Behavior if there is only one player on the site
			if (players.length === 1) {
				jqPlayer.bind('play timeupdate', {player: player}, checkTime)
					.bind('pause', {player: player}, addressCurrentTime);
				// disabled 'cause it overrides chapter clicks
				// bind seeked to addressCurrentTime

				checkCurrentURL();

				// handle browser history navigation
				$(window).bind('hashchange onpopstate', checkCurrentURL);
			}

			// always update Chaptermarks though
			jqPlayer.bind('timeupdate', function () {
				updateChapterMarks(player, marks);
			});

			// update play/pause status
			jqPlayer.bind('play playing', function(){
				list.find('.paused').removeClass('paused');
				if (metainfo.length === 1) {
					metainfo.find('.bigplay').addClass('playing');
				}
			});
			jqPlayer.bind('pause', function(){
				if (metainfo.length === 1) {
					metainfo.find('.bigplay').removeClass('playing');
				}
			});

		});
	};


	/**
	 * return number as string lefthand filled with zeros
	 * @param number number
	 * @param width number
	 * @return string
	 **/
	var zeroFill = function(number, width) {
		width -= number.toString().length;
		return width > 0 ? new Array(width + 1).join('0') + number : number + '';
	};


	/**
	 * accepts array with start and end time in seconds
	 * returns timecode in deep-linking format
	 * @param times array
	 * @param forceHours bool (optional)
	 * @return string
	 **/
	var generateTimecode = $.generateTimecode = function(times, forceHours) {
		function generatePart(seconds) {
			var part, hours, milliseconds;
			// prevent negative values from player
			if (!seconds || seconds <= 0) {
				return forceHours ? '00:00:00' : '00:00';
			}

			// required (minutes : seconds)
			part = zeroFill(Math.floor(seconds / 60) % 60, 2) + ':' +
					zeroFill(Math.floor(seconds % 60) % 60, 2);

			hours = zeroFill(Math.floor(seconds / 60 / 60), 2);
			hours = hours === '00' && !forceHours ? '' : hours + ':';
			milliseconds = zeroFill(Math.floor(seconds % 1 * 1000), 3);
			milliseconds = milliseconds === '000' ? '' : '.' + milliseconds;

			return hours + part + milliseconds;
		}

		if (times[1] > 0 && times[1] < 9999999 && times[0] < times[1]) {
			return generatePart(times[0]) + ',' + generatePart(times[1]);
		}

		return generatePart(times[0]);
	};

	/**
	 * parses time code into seconds
	 * @param string timecode
	 * @return number
	 **/
	var parseTimecode = function(timecode) {
		var parts, startTime = 0, endTime = 0;

		if (timecode) {
			parts = timecode.match(timecodeRegExp);

			if (parts && parts.length === 10) {
				// hours
				startTime += parts[1] ? parseInt( parts[1], 10) * 60 * 60 : 0;
				// minutes
				startTime += parseInt( parts[2], 10) * 60;
				// seconds
				startTime += parseInt( parts[3], 10);
				// milliseconds
				startTime += parts[4] ? parseFloat( parts[4]) : 0;
				// no negative time
				startTime = Math.max(startTime, 0);

				// if there only a startTime but no endTime
				if (parts[5] === undefined) {
					return [startTime, false];
				}

				// hours
				endTime += parts[6] ? parseInt( parts[6], 10) * 60 * 60 : 0;
				// minutes
				endTime += parseInt( parts[7], 10) * 60;
				// seconds
				endTime += parseInt( parts[8], 10);
				// milliseconds
				endTime += parts[9] ? parseFloat( parts[9]) : 0;
				// no negative time
				endTime = Math.max(endTime, 0);

				return (endTime > startTime) ? [startTime, endTime] : [startTime, false];
			}
		}
		return false;
	};

	var checkCurrentURL = function() {
		var deepLink;
		deepLink = parseTimecode(location.href);
		if (deepLink !== false) {
			startAtTime = deepLink[0];
			stopAtTime = deepLink[1];
		}
	};

	var setFragmentURL = function(fragment) {
		location.hash = fragment;
	};

	// update the chapter list when the data is loaded
	var updateChapterMarks = function(player, marks) {
		var doLinkMarks = marks.closest('table').hasClass('linked');

		marks.each(function () {
			var deepLink,
				mark       = $(this),
				startTime  = mark.data('start'),
				endTime    = mark.data('end'),
				isEnabled  = mark.data('enabled'),
				// isBuffered = player.buffered.end(0) > startTime,
				isActive   = player.currentTime > startTime - 0.3 &&
						player.currentTime <= endTime;

			// prevent timing errors
			if (player.buffered.length > 0) {
			  var isBuffered = player.buffered.end(0) > startTime;
			}

			if (isActive) {
				mark.addClass('active').siblings().removeClass('active');
			}
			if (!isEnabled && isBuffered) {
				deepLink = '#t=' + generateTimecode([startTime, endTime]);
				$(mark).data('enabled', true).addClass('loaded').find('a[rel=player]').removeClass('disabled');
			}
		});
	};

	var checkTime = function (e) {
		if (players.length > 1) { return; }
		var player = e.data.player;
		if (startAtTime !== false && 
				//Kinda hackish: Make sure that the timejump is at least 1 second (fix for OGG/Firefox)
				(typeof player.lastCheck === 'undefined' || Math.abs(startAtTime - player.lastCheck) > 1)) {
			player.setCurrentTime(startAtTime);
			player.lastCheck = startAtTime;
			startAtTime = false;
		}
		if (stopAtTime !== false && player.currentTime >= stopAtTime) {
			player.pause();
			stopAtTime = false;
		}
	};

	var addressCurrentTime = function(e) {
		var fragment;
		if (players.length === 1) {
			fragment = 't=' + generateTimecode([e.data.player.currentTime]);
			setFragmentURL(fragment);
		}
	};

	var eventHandler = {
		clickInfowindow: function(event){
			event.preventDefault();

			var summary = event.data.summary;

			summary.podlovewebplayer('toggleHeight');
		}
	};
	
}(jQuery));
