package markdown_to_html

import (
	"encoding/json"
	"fmt"
	"net/http"

	"github.com/GoogleCloudPlatform/functions-framework-go/functions"

	"github.com/gomarkdown/markdown"
	"github.com/gomarkdown/markdown/html"
	"github.com/gomarkdown/markdown/parser"
)

func mdToHTML(md []byte) []byte {
	extensions := parser.CommonExtensions | parser.AutoHeadingIDs | parser.NoEmptyLineBeforeBlock
	p := parser.NewWithExtensions(extensions)
	doc := p.Parse(md)

	htmlFlags := html.CommonFlags | html.HrefTargetBlank
	opts := html.RendererOptions{Flags: htmlFlags}
	renderer := html.NewRenderer(opts)

	return markdown.Render(doc, renderer)
}

func init() {
	functions.HTTP("main", main)
}

func main(w http.ResponseWriter, r *http.Request) {
	var d struct {
		Data string `json:"data"`
	}
	if r.Method == "GET" {
		fmt.Fprintf(w, "ok")
		return
	}

	if err := json.NewDecoder(r.Body).Decode(&d); err != nil {
		http.Error(w, "Missing data", 400)
		return
	}

	type Response struct {
		Result string
	}

	md := []byte(d.Data)
	output, _ := json.Marshal(Response{string(mdToHTML(md))})

	fmt.Fprintf(w, string(output))
}
