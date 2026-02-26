<style data-navigate-once>html.dark{color-scheme:dark}html:not(.dark){color-scheme:light}html:not([data-bt-theme]) body{opacity:0}</style>
<script data-navigate-once>
(function(){
    var d=document.documentElement;
    function c(){
        var s=localStorage.getItem('theme');
        return s==='dark'||(s!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);
    }
    function a(){
        var k=c();
        d.classList.toggle('dark',k);
        d.style.colorScheme=k?'dark':'light';
        d.setAttribute('data-bt-theme',k?'dark':'light');
        try{document.cookie='bt_theme='+(k?'dark':'light')+';path=/;max-age=31536000;SameSite=Lax'}catch(e){}
    }
    a();
    if(!window.__btThemeGuard){
        window.__btThemeGuard=true;
        new MutationObserver(function(){
            var k=c(),h=d.classList.contains('dark');
            if(h!==k){
                d.classList.toggle('dark',k);
                d.style.colorScheme=k?'dark':'light';
                d.setAttribute('data-bt-theme',k?'dark':'light');
            }
        }).observe(d,{attributes:true,attributeFilter:['class']});
        document.addEventListener('livewire:navigated',a);
    }
})();
</script>
