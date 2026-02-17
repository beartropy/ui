<script>
(function(){
    var d=document.documentElement,
        s=localStorage.getItem('theme'),
        k=s==='dark'||(s!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);
    d.classList.toggle('dark',k);
    d.style.colorScheme=k?'dark':'light';
    window.__btThemeNavigated=true;
    document.addEventListener('livewire:navigated',function(){
        var s=localStorage.getItem('theme'),
            k=s==='dark'||(s!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);
        d.classList.toggle('dark',k);
        d.style.colorScheme=k?'dark':'light';
    });
})();
</script>
