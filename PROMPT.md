Build FAQ Rewrite Desk in THIS folder: C:\xampp\htdocs\faq-rewrite-desk

A recruiter-ready demo that rewrites a Harbor Desk FAQ into a shorter, plain-language answer while keeping the same facts.

Laravel 13 + Vue 3 starter kit is already here if present. Reuse it. Do not create a second app inside NewPortfolio.

## Must satisfy

- Models: `RewriteJob` (source, status), `Rewrite` (rewrite_job_id, rewritten, kept_facts json).
- Deterministic `App\Services\Faq\FactKeeper` extracts order-id-like tokens, emails, and numbers from the source. Optional LLM rewrite must include those facts; if the rewrite drops a fact, mark it invalid. Timeout 12s. Never 500.
- `App\Services\Llm\LlmClient` with `FakeLlmClient` in tests and when no `GEMINI_API_KEY`. Optional Gemini when key is set.
- Seed 3 FAQs with a number or order id. UI: paste FAQ, show rewrite, list kept facts, invalid flag.
- Pest: a FAQ with HD-1001 keeps HD-1001 in FakeLlm output; FakeLlm bound; guest POST 200.
- README mermaid: FAQ → extract facts → LLM rewrite → fact check. How to run without API key.

## GitHub

- git config user.name "Oscar Munoz" / user.email "oscarxxi@gmail.com"
- Commit: feat: add faq rewrite desk
- Public https://github.com/oscarmmi/faq-rewrite-desk
- gh repo edit --description "Rewrite Harbor Desk FAQs into plain language without dropping numbers or order IDs (Laravel + Vue)." --add-topic laravel --add-topic vue --add-topic ai --add-topic php --add-topic nlp
- Stop. Print the URL.

No Impeccable. No Redis. No real customers.
