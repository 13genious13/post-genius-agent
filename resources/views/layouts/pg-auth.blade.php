<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#f8faff">
    <title>{{ $title ?? 'Account' }} | PostGenius Agent</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
      :root{font-family:'DM Sans',system-ui,sans-serif;color:#18213d;background:#f8faff;font-synthesis:none}*{box-sizing:border-box}body{margin:0}a{color:inherit;text-decoration:none}button,input{font:inherit}button{cursor:pointer}.page{min-height:100svh;display:grid;grid-template-columns:minmax(0,1fr) minmax(420px, .92fr)}.left{padding:32px clamp(24px,6vw,96px);display:flex;flex-direction:column;max-width:900px;width:100%;margin:auto}.brand{display:inline-flex;align-items:center;gap:12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:21px;font-weight:800;letter-spacing:-.8px}.mark{height:40px;width:40px;display:grid;place-items:center;border-radius:13px;background:linear-gradient(145deg,#5368ef,#8b5cf6);color:white;box-shadow:0 10px 25px #5b60ec38;font-size:22px}.brand em{font-style:normal;color:#6657dd}.back{font-size:13px;color:#64708d;margin-top:34px;display:inline-flex;align-items:center;gap:8px}.content{width:100%;max-width:430px;margin:clamp(40px,7vh,90px) auto 20px}.eyebrow{font-size:12px;text-transform:uppercase;letter-spacing:2px;font-weight:800;color:#6657dd}.heading{font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(30px,3vw,40px);line-height:1.16;letter-spacing:-1.8px;margin:13px 0}.sub{font-size:15px;line-height:1.65;color:#69758f;margin:0 0 30px}.field{margin-bottom:18px}.field label{display:block;font-weight:700;font-size:13px;margin-bottom:9px}.field input{width:100%;border:1px solid #dce3f1;background:#fff;border-radius:13px;padding:15px 16px;outline:none;transition:.2s;color:#1d2740}.field input:focus{border-color:#7065ed;box-shadow:0 0 0 4px #7165ed1b}.field input::placeholder{color:#a3aec2}.field-error{color:#b42318;font-size:12px;margin-top:7px}.meta{display:flex;justify-content:space-between;align-items:center;gap:12px;margin:5px 0 24px;font-size:13px}.meta a,.switch a{color:#6255dd;font-weight:800}.check{display:inline-flex;gap:9px;align-items:center;color:#65718b}.check input{accent-color:#6758e9;width:16px;height:16px}.submit{width:100%;padding:16px;border:0;border-radius:13px;color:white;font-weight:800;background:linear-gradient(105deg,#4f65e9,#8056eb);box-shadow:0 14px 24px #635fe536;transition:transform .2s,box-shadow .2s}.submit:hover{transform:translateY(-2px);box-shadow:0 18px 28px #635fe54b}.switch{text-align:center;font-size:14px;color:#6b7690;margin-top:24px}.foot{margin-top:auto;padding-top:42px;color:#9aa4b7;font-size:12px;display:flex;gap:16px;flex-wrap:wrap}.foot a:hover{text-decoration:underline}.alert{padding:13px 15px;border-radius:12px;margin-bottom:20px;font-size:13px;background:#edf9f2;color:#176a3d;border:1px solid #c7efd8}.errors{background:#fff1f2;border:1px solid #fecdd3;color:#a71d37;padding:13px 15px;border-radius:12px;margin-bottom:20px;font-size:13px}.errors ul{margin:0;padding-left:18px}.right{position:relative;overflow:hidden;background:radial-gradient(circle at 90% 4%,#c1b8ff 0,transparent 30%),radial-gradient(circle at 15% 90%,#a8dcff 0,transparent 37%),linear-gradient(145deg,#edf0ff,#e7ecff 55%,#eff8ff);padding:45px clamp(26px,5vw,75px);display:flex;flex-direction:column;justify-content:center}.right:before{content:'';position:absolute;inset:0;opacity:.32;background-image:radial-gradient(#8989c9 1px,transparent 1px);background-size:26px 26px;mask-image:linear-gradient(90deg,transparent,#000)}.visual{position:relative;z-index:1;max-width:550px;margin:auto;width:100%}.tag{display:inline-flex;align-items:center;gap:8px;border:1px solid #ffffffb0;background:#ffffffb0;padding:9px 13px;border-radius:999px;font-size:12px;color:#6555d8;font-weight:800;box-shadow:0 5px 25px #6261b01c}.visual h2{font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(30px,3.2vw,48px);letter-spacing:-2px;line-height:1.14;margin:22px 0 16px}.visual h2 span{color:#6558e8}.visual>p{color:#63718d;line-height:1.75;font-size:15px;max-width:450px}.dashboard{margin-top:38px;background:#ffffffed;border:1px solid #fff;border-radius:24px;padding:19px;box-shadow:0 25px 70px #6877be36;transform:rotate(-2deg)}.dash-top{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e9edf7;padding-bottom:15px}.dots{display:flex;gap:5px}.dots i{height:7px;width:7px;border-radius:50%;background:#cbd3e4}.dash-label{font-size:12px;font-weight:800}.live{font-size:10px;color:#16845e;background:#e7f8ef;padding:6px 9px;border-radius:50px}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin:17px 0}.stat{background:#f6f7ff;padding:14px;border-radius:13px}.stat small{font-size:10px;color:#7d87a0;display:block}.stat strong{font-size:22px;display:block;margin-top:8px;letter-spacing:-1px}.stat b{font-size:10px;color:#22a477}.chart{height:115px;display:flex;align-items:end;gap:10px;padding:8px 4px 0;border-bottom:1px solid #e5e9f4}.bar{flex:1;border-radius:7px 7px 0 0;background:linear-gradient(#7564f0,#b8b5ff)}.workflow{display:flex;flex-wrap:wrap;gap:8px;margin-top:18px}.chip{font-size:11px;color:#586481;border:1px solid #e5e8f5;background:#fafbff;padding:9px 11px;border-radius:9px}.chip.active{color:#6553d6;background:#f0edff;border-color:#ded7ff}.trust{margin-top:35px;display:flex;align-items:center;gap:12px;font-size:12px;color:#77839b}.avatars{display:flex}.avatars span{display:grid;place-items:center;height:30px;width:30px;border-radius:50%;border:2px solid white;background:#d6d9ff;margin-left:-6px;font-weight:800;color:#5d56c9}.avatars span:first-child{margin-left:0}@media(max-width:950px){.page{grid-template-columns:1fr}.right{display:none}.left{min-height:100svh;padding:24px}.content{margin:55px auto 35px}}@media(max-width:420px){.heading{font-size:30px}.brand{font-size:19px}.meta{align-items:flex-start}.foot{padding-top:25px}}
    </style>
</head>
<body>
<main class="page">
 <section class="left">
  <header><a class="brand" href="{{ url('/') }}" aria-label="PostGenius Agent homepage"><span class="mark">✦</span><span>PostGenius <em>Agent</em></span></a></header>
  <a class="back" href="{{ url('/') }}">← Back to homepage</a>
  <div class="content">
   <div class="eyebrow">{{ $eyebrow ?? 'Your AI marketing workspace' }}</div>
   <h1 class="heading">{{ $heading ?? 'Welcome to PostGenius Agent' }}</h1>
   <p class="sub">{{ $subtitle ?? '' }}</p>
   @if(session('status')) <div class="alert" role="status">{{ session('status') }}</div> @endif
   @if($errors->any()) <div class="errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
   {{ $slot }}
  </div>
  <footer class="foot"><span>© {{ date('Y') }} PostGenius Agent</span><a href="{{ url('/privacy') }}">Privacy</a><a href="{{ url('/terms') }}">Terms</a></footer>
 </section>
 <aside class="right" aria-label="PostGenius Agent product overview">
  <div class="visual">
   <span class="tag">✦ Your AI-powered marketing team</span>
   <h2>From ideas to impact. <span>On autopilot.</span></h2>
   <p>Research your audience, plan smarter campaigns, create content and turn performance into better decisions — all in one workspace.</p>
   <div class="dashboard" aria-hidden="true">
    <div class="dash-top"><div class="dots"><i></i><i></i><i></i></div><span class="dash-label">Marketing Overview</span><span class="live">● Workspace preview</span></div>
    <div class="stats"><div class="stat"><small>Campaigns</small><strong>12</strong><b>↗ Growing</b></div><div class="stat"><small>Content drafts</small><strong>48</strong><b>↗ On track</b></div><div class="stat"><small>AI agents</small><strong>7</strong><b>✦ Connected</b></div></div>
    <div class="chart"><span class="bar" style="height:35%"></span><span class="bar" style="height:55%"></span><span class="bar" style="height:42%"></span><span class="bar" style="height:68%"></span><span class="bar" style="height:58%"></span><span class="bar" style="height:80%"></span><span class="bar" style="height:72%"></span><span class="bar" style="height:93%"></span></div>
    <div class="workflow"><span class="chip active">✦ Research</span><span class="chip">Strategy</span><span class="chip">Content</span><span class="chip">Creative</span><span class="chip">Analytics</span></div>
   </div>
   <div class="trust"><div class="avatars"><span>A</span><span>M</span><span>S</span></div><span>Illustrative product preview — not live account data</span></div>
  </div>
 </aside>
</main>
</body>
</html>
