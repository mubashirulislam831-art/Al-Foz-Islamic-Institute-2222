const fs = require('fs');
const content = fs.readFileSync('./superadmin/teachers/add_teacher.php', 'utf-8');
const match = /<script\b[^>]*>([\s\S]*?)<\/script>/i.exec(content);
const script = match[1];
const lines = script.split('\n');
lines.forEach((l, i) => console.log(`${i+1}: ${l}`));
