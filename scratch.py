import json

with open('C:/Users/sachi/.gemini/antigravity/brain/5c04c503-1838-4bbd-aa12-4a5561031d1c/.system_generated/steps/44/output.txt', 'r', encoding='utf-8') as f:
    data = json.load(f)

with open('output_screens.txt', 'w', encoding='utf-8') as out:
    for s in data.get('screens', []):
        name = s.get('name', '')
        screen_id = name.split('/')[-1]
        title = s.get('title', 'No Title')
        out.write(f"- {title} (ID: {screen_id})\n")
