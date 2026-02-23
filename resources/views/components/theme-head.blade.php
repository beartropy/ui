<script>
(function(){
    var d=document.documentElement;
    function t(){
        var s=localStorage.getItem('theme'),
            k=s==='dark'||(s!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);
        d.classList.toggle('dark',k);
        d.style.colorScheme=k?'dark':'light';
    }
    t();
    if(!window.__btThemeGuard){
        window.__btThemeGuard=true;
        new MutationObserver(function(){
            var s=localStorage.getItem('theme'),
                k=s==='dark'||(s!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches),
                h=d.classList.contains('dark');
            if(h!==k){
                d.classList.toggle('dark',k);
                d.style.colorScheme=k?'dark':'light';
            }
        }).observe(d,{attributes:true,attributeFilter:['class']});
    }
})();
</script>
