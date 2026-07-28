const fs = require('fs');
const { execSync } = require('child_process');
const files = execSync('find . -name "*.php" -not -path "*/node_modules/*"').toString().split('\n').filter(Boolean);

let errorFound = false;

for (const file of files) {
  const content = fs.readFileSync(file, 'utf-8');
  const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gi;
  let match;
  let i = 0;
  while ((match = scriptRegex.exec(content)) !== null) {
    const scriptContent = match[1];
    if (scriptContent.trim()) {
      fs.writeFileSync('temp.js', scriptContent);
      try {
        execSync('node --check temp.js', { stdio: 'pipe' });
      } catch (err) {
        console.error(`Syntax error in ${file} (script tag ${i + 1}):`);
        console.error(err.stderr.toString());
        errorFound = true;
      }
    }
    i++;
  }
}

if (!errorFound) {
  console.log('No syntax errors found in PHP scripts.');
}
