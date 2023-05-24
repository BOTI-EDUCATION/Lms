if ($("#meet_vc_boti").length) {
	navigator.mediaDevices.getUserMedia({
			video: true,
			audio: true
		})
		.then(stream => video.srcObject = stream)
		.catch(e => console.log(e.name + ": " + e.message));

	var domain = app.video_conference.serveur;
	var options = {
		roomName: app.video_conference.roomName,
		width: '100%',
		parentNode: document.querySelector('#meet_vc_boti'),
		interfaceConfigOverwrite: {
			MOBILE_APP_PROMO: false,
			disableDeepLinking: true,
			DEFAULT_BACKGROUND: '#3c96ec',
			SHOW_CHROME_EXTENSION_BANNER: false
		},
		configOverwrite: {
			disableDeepLinking: true
		}
	}
	var api = new JitsiMeetExternalAPI(domain, options);
	api.executeCommand('displayName', app.video_conference.displayName);
	api.executeCommand('email', app.video_conference.email);
	api.executeCommand('avatarUrl', app.video_conference.avatarUrl);
	//api.executeCommand('toggleLobby', true);
	api.executeCommand('subject', 'New Conference Subject');
	api.executeCommand('toggleTileView');
	api.executeCommand('toggleChat');
	//api.executeCommand('muteEveryone');
	if (app.video_conference.prof)
		api.executeCommand('toggleShareScreen');

}