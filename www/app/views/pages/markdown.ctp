<h2>Markdown turns plain text into HTML</h2>

<h3>Paragraphs</h3>

<p>Leave a blank line between blocks of text to create paragraphs.</p>

<pre>
This is the first paragraphs.
This is also the first paragraph.

This is the second paragraph.
</pre>

<h3>Linebreaks</h3>

<p>To create a manual linebreak, end a line with two spaces.</p>

<pre>
The first line of text.<span class="hi">  </span>
The second line.
</pre>

<h3>Headers</h3>

<p>Underline a piece of text to create level 1 or level 2 headers:</p>

<pre>
Level 1
<span class="hi">=======</span>

Level 2
<span class="hi">-------</span>
</pre>

<p>Alternatively, you can use hash marks to create headers up to level 6:</p>

<pre>
<span class="hi">#</span> Level 1 <span class="hi">#</span>
<span class="hi">##</span> Level 2 <span class="hi">##</span>
<span class="hi">###</span> Level 3 <span class="hi">###</span>
</pre>

<h3>Bold and italics</h3>

<p>To make a word of phrase italics, wrap it in single asterisks or underscrores:</p>

<pre>The <span class="hi">*</span>word<span class="hi">*</span> is in <span class="hi">_</span>italics<span class="hi">_</span></pre>

<p>To make a word of phrase bold, wrap it in double asterisks or underscrores:</p>

<pre>The <span class="hi">**</span>word<span class="hi">**</span> is in <span class="hi">__</span>bold<span class="hi">__</span></pre>

<p>To prevent bold or italics, use a backslash to escape:</p>

<pre>A <span class="hi">\*</span> symbol is the same as a <span class="hi">\_</span> symbol</pre>

<h3>Links</h3>

<p>There are two ways to make links. You can include the URL in the text:</p>

<pre>Here is a link to <span class="hi">[Google](http://www.google.com)</span>.</pre>

<p>Here the word Google will become a link to the Google website. You can also put the URL in footnotes:</p>

<pre>
Here is a link to <span class="hi">[Google][1]</span> and a link to <span class="hi">[Mozilla][moz]</span>.

  <span class="hi">[1]: http://www.google.com</span>
  <span class="hi">[moz]: http://www.mozilla.org</span>
</pre>

<h3>Horizontal rules</h3>

<p>Create horizontal rules by putting three or more dashes, asterisks or underscores on the same line. You can also put spaces between them.</p>

<pre>
<span class="hi">---</span>
<span class="hi">* * * *</span>
<span class="hi">_______</span>
</pre>

<h3>Simple lists</h3>

<p>Use a dash, plus or asterisk to create bulleted lists:</p>

<pre>
<span class="hi">- </span>Item 1
<span class="hi">+ </span>Item 2
<span class="hi">* </span>Item 3
</pre>

<p>Use numbers and dots to create numbered lists:</p>

<pre>
<span class="hi">1. </span>Item 1
<span class="hi">2. </span>Item 2
<span class="hi">4. </span>Item 3
</pre>

<p>Markdown keeps track of the numbers, so the last item will be number 3, not number 4.</p>

<h3>Nested lists</h3>

<p>Use four spaces to indent and create nested lists. You can mix bulleted and numbered lists:</p>

<pre>
<span class="hi">1. </span>Item 1
<span class="hi">    - </span>Item 1.1
<span class="hi">    - </span>Item 1.2
<span class="hi">2. </span>Item 2. This item contains multiple
<span class="hi">   </span>paragraphs and multiple lines.
<span class="hi">   </span>
<span class="hi">   </span>This is the second paragraph.
<span class="hi">   </span>
<span class="hi">4. </span>Item 3
<span class="hi">    1. </span>Item 3.1
<span class="hi">        + </span>Item 3.1.1
<span class="hi">        + </span>Item 3.1.2
<span class="hi">    2. </span>Item 3.2
</pre>

<h3>Quotes</h3>

<p>Quotes work the same as e-mail. Start you lines with a &gt;. Use multiple &gt;'s for nested quotes</p>

<pre>
<span class="hi">&gt; &gt; </span>Did he really say that
<span class="hi">&gt; &gt; </span>out loud?
<span class="hi">&gt; </span>
<span class="hi">&gt; </span>Yes, he did!
</pre>

<h3>Images</h3>

<p>Images work the same as links, except they have an exlamation mark in front of them. The link text becomes the image alt text.</p>

<pre>
This is an <span class="hi">![icon](http://www.example.org/icon.png)</span>.
Here is <span class="hi">![another icon][1]</span>.

  <span class="hi">[1]: http://www.example.org/icon-2.png</span>
</pre>

<h3>Complete syntax</h3>

<p>You can find the complete Markdown syntax at <a href="http://daringfireball.net/projects/markdown/syntax">Daring Fireball</a>. Officeshots
supports the entire Markdown syntax, except that we do not allow HTML code.</p>
