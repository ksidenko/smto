<com:TPanel ID="RunPanel">

  <com:TPanel Id="MenuPanel">

    <com:TClientScript>
      function showPanel(pid) { 
        $('<%= $this->PdfPanel->ClientID %>').style.display = (pid == "PDF" ? "block" : "none"); 
        $('<%= $this->LogPanel->ClientID %>').style.display = (pid == "LOG" ? "block" : "none"); 
        $('<%= $this->XmlPanel->ClientID %>').style.display = (pid == "XML" ? "block" : "none"); 
        $('<%= $this->DebugPanel->ClientID %>').style.display = (pid == "DEBUG" ? "block" : "none"); 
        }
    </com:TClientScript>

    <div id="topmenu" style="Xposition:fixed;bottom:0;left:0;z-index:10;font-size: 75%">
      <com:THyperLink CssClass="topmenu_link" Text="PDF" Attributes.onclick="showPanel('PDF');" />
      <com:THyperLink CssClass="topmenu_link" Text="LOG" Attributes.onclick="showPanel('LOG');" />
      <com:THyperLink CssClass="topmenu_link" Text="SOURCE" Attributes.onclick="showPanel('XML');" />
      <com:THyperLink CssClass="topmenu_link" Text="DEBUG" Attributes.onclick="showPanel('DEBUG');" />
    </div>

      <com:TPanel CssClass="RPPanel" ID="PdfPanel" ></com:TPanel>
      <com:TPanel CssClass="RPPanel" ID="LogPanel" Style="display:none"></com:TPanel>
      <com:TPanel CssClass="RPPanel" ID="XmlPanel" Style="display:none">
        <com:TTextHighlighter ID="XMLhighlight" ShowLineNumbers="true" Language="xml" />
      </com:TPanel>
      <com:TPanel CssClass="RPPanel" ID="DebugPanel" Style="display:none"></com:TPanel>

  </com:TPanel>
</com:TPanel>