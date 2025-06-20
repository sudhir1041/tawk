const widgets = [
  { id_lang_zaptool: 1, lang: 'EN', script: '<script>/* ZapTool script */</script>' },
  { id_lang_zaptool: 2, lang: 'ES', script: '<script>/* ZapTool script */</script>' },
];

ReactDOM.render(<WidgetForm widgets={widgets} />, document.getElementById('root'));
