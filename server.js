const http = require('http');
const fs = require('fs');
const path = require('path');

const publicDir = path.join(__dirname, 'react');

const server = http.createServer((req, res) => {
  let filePath = path.join(publicDir, req.url === '/' ? '/index.html' : req.url);
  const ext = path.extname(filePath).toLowerCase();
  const map = {
    '.html': 'text/html',
    '.js': 'text/javascript',
    '.jsx': 'text/jsx',
    '.css': 'text/css',
    '.png': 'image/png',
    '.gif': 'image/gif',
  };

  fs.readFile(filePath, (err, content) => {
    if (err) {
      res.writeHead(404);
      res.end('Not found');
      return;
    }
    res.writeHead(200, { 'Content-Type': map[ext] || 'text/plain' });
    res.end(content);
  });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`ZapTool server running on port ${PORT}`);
});
