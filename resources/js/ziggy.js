const Ziggy = {"url":"http:\/\/midas-reporting.test:3000","port":3000,"defaults":{},"routes":{"livewire.message":{"uri":"livewire\/message\/{name}","methods":["POST"]},"livewire.upload-file":{"uri":"livewire\/upload-file","methods":["POST"]},"livewire.preview-file":{"uri":"livewire\/preview-file\/{filename}","methods":["GET","HEAD"]},"dashboard":{"uri":"\/","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"bcp.records":{"uri":"bcp-records","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"visas":{"uri":"visas","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"insights":{"uri":"insights","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"reports":{"uri":"reports","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"border_points":{"uri":"border-points","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"movement.index":{"uri":"movement","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"movement.summary":{"uri":"movement\/summary","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"movement.":{"uri":"movement\/traffic","methods":["GET","HEAD"],"domain":"midas-reporting.test"},"movement.demographics":{"uri":"movement\/demographics\/{type}","methods":["GET","HEAD"],"domain":"midas-reporting.test"}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    for (let name in window.Ziggy.routes) {
        Ziggy.routes[name] = window.Ziggy.routes[name];
    }
}

export { Ziggy };
