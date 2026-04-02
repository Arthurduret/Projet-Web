<footer>
    <a href="/index.php?page=mentions_legales">Mentions Légales</a>
    <p>✦</p>
    <a href="/index.php?page=cgu">CGU</a>
    <p>✦</p>
    <a href="/index.php?page=cookies">Gestion des cookies</a>
</footer>

<!-- BANNIÈRE COOKIES — EN DEHORS DU FOOTER -->
<?php if (!isset($_COOKIE['cookies_jobeo'])): ?>
<div id="cookie-overlay" style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:99999;pointer-events:none;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.5);">
<div id="cookie-banner" style="pointer-events:all;">
<style>
#cookie-banner{background:#1e2a56;border-radius:20px;padding:1.2rem 1.5rem;overflow:hidden;max-width:680px;width:90vw;animation:cbSlideUp .6s cubic-bezier(.22,1,.36,1)}
@keyframes cbSlideUp{from{transform:translateY(40px);opacity:0}to{transform:translateY(0);opacity:1}}
@keyframes cbSteam{0%,100%{transform:translateY(0);opacity:.7}50%{transform:translateY(-16px);opacity:.05}}
@keyframes cbGlow{0%,100%{opacity:.7}50%{opacity:1}}
@keyframes cbWalk{0%{transform:translateX(320px)}100%{transform:translateX(0)}}
@keyframes cbDoor{0%{transform:scaleY(1);transform-origin:bottom}100%{transform:scaleY(0);transform-origin:bottom}}
@keyframes cbTray{0%{transform:translateX(-80px) translateY(10px);opacity:0}100%{transform:translateX(0) translateY(0);opacity:1}}
@keyframes cbPresent{0%,100%{transform:rotate(-2deg)}50%{transform:rotate(2deg)}}
@keyframes cbMoustache{0%,100%{transform:scaleX(1)}50%{transform:scaleX(1.08)}}
#cb-scene{display:flex;align-items:flex-end;justify-content:center;height:200px;position:relative;margin-bottom:1rem}
#cb-oven-body{width:180px;height:170px;background:#5a5a5a;border-radius:12px;position:relative;flex-shrink:0}
#cb-oven-stripe{position:absolute;top:0;left:0;right:0;height:32px;background:#6a6a6a;border-radius:12px 12px 0 0}
#cb-knobs{display:flex;gap:12px;justify-content:center;padding-top:10px}
.cb-knob{width:11px;height:11px;border-radius:50%;background:#222;border:2px solid #999}
#cb-win{position:absolute;top:38px;left:10px;right:10px;height:90px;background:#222;border-radius:6px;overflow:hidden}
#cb-glow{position:absolute;inset:0;background:#ff7700;opacity:.85;animation:cbGlow 1.2s ease-in-out infinite}
#cb-door{position:absolute;top:0;left:0;right:0;bottom:0;background:#444;border-radius:6px;transform-origin:bottom;animation:cbDoor .7s 1.2s ease forwards}
#cb-door-glass{position:absolute;top:8px;left:8px;right:8px;bottom:8px;background:#333;border-radius:3px}
#cb-handle{position:absolute;bottom:40px;left:15px;right:15px;height:10px;background:#888;border-radius:5px}
#cb-obottom{position:absolute;bottom:0;left:0;right:0;height:34px;background:#555;border-radius:0 0 12px 12px;border-top:3px solid #444}
#cb-tray{position:absolute;bottom:28px;left:50%;transform:translateX(-50%);animation:cbTray .6s 2s ease both;z-index:10}
#cb-steams{display:flex;gap:8px;justify-content:space-around;padding:0 15px;height:30px;align-items:flex-end;margin-bottom:2px}
.cb-sv{width:5px;border-radius:4px;background:rgba(255,255,255,.55)}
.cb-s1{height:22px;animation:cbSteam 1.2s .0s ease-in-out infinite}
.cb-s2{height:28px;animation:cbSteam 1.2s .2s ease-in-out infinite}
.cb-s3{height:18px;animation:cbSteam 1.2s .4s ease-in-out infinite}
.cb-s4{height:24px;animation:cbSteam 1.2s .15s ease-in-out infinite}
.cb-s5{height:20px;animation:cbSteam 1.2s .35s ease-in-out infinite}
.cb-s6{height:26px;animation:cbSteam 1.2s .1s ease-in-out infinite}
#cb-chef{animation:cbWalk .9s 1.8s ease both;margin-left:-20px;z-index:5}
#cb-chef-inner{animation:cbPresent 2s 2.8s ease-in-out infinite}
#cb-msg{text-align:center;margin-bottom:1rem}
#cb-msg h3{color:#fff;font-size:15px;font-weight:500;margin:0 0 4px}
#cb-msg p{color:rgba(255,255,255,.65);font-size:13px;margin:0}
#cb-msg a{color:#f17526}
#cb-btns{display:flex;gap:12px;justify-content:center}
.cb-btn-r{padding:10px 22px;border-radius:25px;border:1.5px solid rgba(255,255,255,.4);background:transparent;color:#fff;font-size:14px;cursor:pointer;transition:all .2s}
.cb-btn-r:hover{border-color:#fff;background:rgba(255,255,255,.1)}
.cb-btn-a{padding:10px 26px;border-radius:25px;border:none;background:#f17526;color:#fff;font-size:14px;font-weight:500;cursor:pointer;transition:background .2s}
.cb-btn-a:hover{background:#d4621f}
</style>

<div id="cb-scene">
  <div id="cb-oven-body">
    <div id="cb-oven-stripe">
      <div id="cb-knobs">
        <div class="cb-knob"></div><div class="cb-knob"></div>
        <div class="cb-knob"></div><div class="cb-knob"></div>
      </div>
    </div>
    <div id="cb-win">
      <div id="cb-glow"></div>
      <div id="cb-door"><div id="cb-door-glass"></div></div>
    </div>
    <div id="cb-handle"></div>
    <div id="cb-obottom"></div>
  </div>

  <div id="cb-tray">
    <div id="cb-steams">
      <div class="cb-sv cb-s1"></div><div class="cb-sv cb-s2"></div>
      <div class="cb-sv cb-s3"></div><div class="cb-sv cb-s4"></div>
      <div class="cb-sv cb-s5"></div><div class="cb-sv cb-s6"></div>
    </div>
    <svg width="200" height="65" viewBox="0 0 200 65">
      <rect x="5" y="42" width="190" height="18" rx="4" fill="#777"/>
      <rect x="5" y="38" width="190" height="8" rx="3" fill="#999"/>
      <rect x="3" y="32" width="194" height="12" rx="4" fill="#bbb"/>
      <ellipse cx="100" cy="32" rx="97" ry="7" fill="#ccc"/>
      <ellipse cx="14" cy="52" rx="14" ry="10" fill="#e8980a"/>
      <ellipse cx="14" cy="48" rx="12" ry="8" fill="#f0a80c"/>
      <ellipse cx="186" cy="52" rx="14" ry="10" fill="#e8980a"/>
      <ellipse cx="186" cy="48" rx="12" ry="8" fill="#f0a80c"/>
      <ellipse cx="38" cy="26" rx="20" ry="7" fill="#8B4500"/><ellipse cx="38" cy="24" rx="20" ry="7" fill="#a05500"/><ellipse cx="38" cy="22" rx="18" ry="6" fill="#c07010"/>
      <circle cx="31" cy="20" r="3" fill="#4a2200"/><circle cx="40" cy="24" r="2.5" fill="#4a2200"/><circle cx="47" cy="19" r="2" fill="#4a2200"/>
      <ellipse cx="100" cy="26" rx="20" ry="7" fill="#8B4500"/><ellipse cx="100" cy="24" rx="20" ry="7" fill="#a05500"/><ellipse cx="100" cy="22" rx="18" ry="6" fill="#c07010"/>
      <circle cx="93" cy="20" r="3" fill="#4a2200"/><circle cx="102" cy="24" r="2.5" fill="#4a2200"/><circle cx="109" cy="19" r="2" fill="#4a2200"/>
      <ellipse cx="162" cy="26" rx="20" ry="7" fill="#8B4500"/><ellipse cx="162" cy="24" rx="20" ry="7" fill="#a05500"/><ellipse cx="162" cy="22" rx="18" ry="6" fill="#c07010"/>
      <circle cx="155" cy="20" r="3" fill="#4a2200"/><circle cx="164" cy="24" r="2.5" fill="#4a2200"/><circle cx="171" cy="19" r="2" fill="#4a2200"/>
      <ellipse cx="38" cy="38" rx="20" ry="7" fill="#8B4500"/><ellipse cx="38" cy="36" rx="20" ry="7" fill="#a05500"/><ellipse cx="38" cy="34" rx="18" ry="6" fill="#c07010"/>
      <circle cx="31" cy="32" r="3" fill="#4a2200"/><circle cx="43" cy="36" r="2.5" fill="#4a2200"/><circle cx="45" cy="30" r="2" fill="#4a2200"/>
      <ellipse cx="100" cy="38" rx="20" ry="7" fill="#8B4500"/><ellipse cx="100" cy="36" rx="20" ry="7" fill="#a05500"/><ellipse cx="100" cy="34" rx="18" ry="6" fill="#c07010"/>
      <circle cx="93" cy="32" r="3" fill="#4a2200"/><circle cx="105" cy="36" r="2.5" fill="#4a2200"/><circle cx="107" cy="30" r="2" fill="#4a2200"/>
      <ellipse cx="162" cy="38" rx="20" ry="7" fill="#8B4500"/><ellipse cx="162" cy="36" rx="20" ry="7" fill="#a05500"/><ellipse cx="162" cy="34" rx="18" ry="6" fill="#c07010"/>
      <circle cx="155" cy="32" r="3" fill="#4a2200"/><circle cx="167" cy="36" r="2.5" fill="#4a2200"/><circle cx="169" cy="30" r="2" fill="#4a2200"/>
    </svg>
  </div>

  <div id="cb-chef">
    <div id="cb-chef-inner">
      <svg width="100" height="190" viewBox="0 0 100 190">
        <ellipse cx="50" cy="32" rx="24" ry="9" fill="#fff"/>
        <rect x="28" y="12" width="44" height="24" rx="8" fill="#fff"/>
        <ellipse cx="50" cy="12" rx="22" ry="8" fill="#eee"/>
        <rect x="30" y="8" width="40" height="8" rx="4" fill="#ddd"/>
        <ellipse cx="50" cy="58" rx="22" ry="24" fill="#f5c8a0"/>
        <ellipse cx="35" cy="62" rx="7" ry="5" fill="#f0a0a0" opacity=".5"/>
        <ellipse cx="65" cy="62" rx="7" ry="5" fill="#f0a0a0" opacity=".5"/>
        <ellipse cx="41" cy="53" rx="5" ry="5.5" fill="#fff"/>
        <ellipse cx="59" cy="53" rx="5" ry="5.5" fill="#fff"/>
        <circle cx="42" cy="54" r="3" fill="#2a1a0a"/>
        <circle cx="60" cy="54" r="3" fill="#2a1a0a"/>
        <circle cx="43" cy="53" r="1.2" fill="#fff"/>
        <circle cx="61" cy="53" r="1.2" fill="#fff"/>
        <path d="M36 47 Q41 43 46 47" stroke="#5a3a1a" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <path d="M54 47 Q59 43 64 47" stroke="#5a3a1a" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <ellipse cx="50" cy="62" rx="4.5" ry="3.5" fill="#e8a882"/>
        <g style="animation:cbMoustache 2s ease-in-out infinite;transform-origin:50px 68px">
          <path d="M28 68 Q36 62 43 68 Q46 71 50 69 Q54 71 57 68 Q64 62 72 68" stroke="#3a2010" stroke-width="4" fill="none" stroke-linecap="round"/>
          <path d="M28 68 Q22 65 25 60" stroke="#3a2010" stroke-width="3" fill="none" stroke-linecap="round"/>
          <path d="M72 68 Q78 65 75 60" stroke="#3a2010" stroke-width="3" fill="none" stroke-linecap="round"/>
        </g>
        <path d="M40 76 Q50 84 60 76" stroke="#c0845a" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <rect x="16" y="80" width="68" height="70" rx="12" fill="#fff"/>
        <circle cx="50" cy="94" r="3.5" fill="#ddd"/>
        <circle cx="50" cy="106" r="3.5" fill="#ddd"/>
        <circle cx="50" cy="118" r="3.5" fill="#ddd"/>
        <rect x="32" y="88" width="36" height="60" rx="5" fill="#293771" opacity=".85"/>
        <path d="M16 88 Q-5 92 -15 100" stroke="#fff" stroke-width="16" stroke-linecap="round" fill="none"/>
        <path d="M16 88 Q-5 92 -15 100" stroke="#f5c8a0" stroke-width="12" stroke-linecap="round" fill="none"/>
        <path d="M84 88 Q95 100 92 118" stroke="#fff" stroke-width="16" stroke-linecap="round" fill="none"/>
        <path d="M84 88 Q95 100 92 118" stroke="#f5c8a0" stroke-width="12" stroke-linecap="round" fill="none"/>
        <rect x="26" y="148" width="20" height="36" rx="8" fill="#1e2a56"/>
        <rect x="54" y="148" width="20" height="36" rx="8" fill="#1e2a56"/>
        <ellipse cx="36" cy="184" rx="14" ry="7" fill="#222"/>
        <ellipse cx="64" cy="184" rx="14" ry="7" fill="#222"/>
      </svg>
    </div>
  </div>
</div>

<div id="cb-msg">
  <h3>Nos cookies sortent du four !</h3>
  <p>Acceptez-vous nos cookies pour une meilleure expérience ? <a href="/index.php?page=cookies">En savoir plus</a></p>
</div>

<div id="cb-btns">
  <button class="cb-btn-r" onclick="cbRefuser()">Non merci</button>
  <button class="cb-btn-a" onclick="cbAccepter()">Accepter les cookies</button>
</div>

</div>
</div>

<script>
function cbCookieExiste() {
    return document.cookie.split(';').some(c => c.trim().startsWith('cookies_jobeo='));
}
if (cbCookieExiste()) {
    const overlay = document.getElementById('cookie-overlay');
    if (overlay) overlay.style.display = 'none';
}
function cbAccepter() {
    const expires = new Date(Date.now() + 365*24*60*60*1000).toUTCString();
    document.cookie = "cookies_jobeo=1; path=/; expires=" + expires + "; samesite=strict; secure";
    const b = document.getElementById('cookie-banner');
    b.innerHTML = '<p style="color:#fff;text-align:center;padding:1.5rem 0;font-size:16px;font-weight:500">Merci ! Bonne visite !</p>';
    setTimeout(() => document.getElementById('cookie-overlay').style.display = 'none', 2000);
}
function cbRefuser() {
    const expires = new Date(Date.now() + 365*24*60*60*1000).toUTCString();
    document.cookie = "cookies_jobeo=0; path=/; expires=" + expires + "; samesite=strict; secure";
    document.getElementById('cookie-overlay').style.display = 'none';
}
</script>
<?php endif; ?>