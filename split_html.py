import re

with open(r'd:\KareOns\kare-ons\resources\views\stitch_home.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

# Extract head contents (from <head> to </head>)
head_match = re.search(r'<head>(.*?)</head>', html, re.DOTALL)
head_content = head_match.group(1) if head_match else ''

# Extract header
header_match = re.search(r'(<header.*?</header>)', html, re.DOTALL)
header_content = header_match.group(1) if header_match else ''

# Extract main
main_match = re.search(r'<main>(.*?)</main>', html, re.DOTALL)
main_content = main_match.group(1) if main_match else ''

# Extract footer
footer_match = re.search(r'(<footer.*?</footer>)', html, re.DOTALL)
footer_content = footer_match.group(1) if footer_match else ''

# Extract scripts at the end
script_match = re.search(r'</footer>\s*(<script>.*?</script>)\s*</body>', html, re.DOTALL)
script_content = script_match.group(1) if script_match else ''

# Now read existing app.blade.php to keep its blade directives
app_layout = f"""<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    {head_content}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-md selection:bg-secondary-fixed selection:text-on-secondary-fixed">
    {header_content}
    
    <main class="flex-grow">
        @yield('content')
    </main>

    {footer_content}
    
    {script_content}
</body>
</html>
"""

home_blade = f"""@extends('layouts.app')

@section('content')
{main_content}
@endsection
"""

with open(r'd:\KareOns\kare-ons\resources\views\layouts\app.blade.php', 'w', encoding='utf-8') as f:
    f.write(app_layout)

with open(r'd:\KareOns\kare-ons\resources\views\home.blade.php', 'w', encoding='utf-8') as f:
    f.write(home_blade)
