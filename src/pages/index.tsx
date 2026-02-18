import { useMemo } from '@wordpress/element';

export type PageItem = {
  id: number;
  title: string;
  permalink: string;
  preview: string | null;
};

export type TemplateItem = {
  slug: string;
  name: string;
  description: string;
};

interface Props {
  pages: PageItem[];
  templates: TemplateItem[];
  selectionType: 'page' | 'template';
  selectedPageId: number;
  selectedTemplateSlug: string;
  onSelectPage: (pageId: number) => void;
  onSelectTemplate: (slug: string) => void;
}

const templateTone: Record<string, string> = {
  classic: 'from-blue-500 to-indigo-500',
  split: 'from-slate-900 to-blue-700',
  minimal: 'from-slate-100 to-slate-300',
};

export const Index = ({
  pages,
  templates,
  selectionType,
  selectedPageId,
  selectedTemplateSlug,
  onSelectPage,
  onSelectTemplate,
}: Props) => {
  const hasPreview = useMemo(() => pages.some((page) => !!page.preview), [pages]);

  return (
    <div className="space-y-8">
      <section>
        <div className="font-semibold text-base mb-1">Built-in maintenance templates</div>
        <p className="text-sm text-gray-500 mb-4">Choose one of the ready-made designs for instant setup.</p>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {templates.map((template) => (
            <button
              key={template.slug}
              className={`border rounded-lg p-3 text-left transition ${
                selectionType === 'template' && selectedTemplateSlug === template.slug
                  ? 'border-blue-500 ring-2 ring-blue-100'
                  : 'border-gray-200'
              }`}
              onClick={() => onSelectTemplate(template.slug)}
            >
              <div
                className={`rounded-md w-full h-28 mb-3 bg-gradient-to-br ${
                  templateTone[template.slug] || 'from-slate-100 to-slate-300'
                }`}
              />
              <div className="font-medium">{template.name}</div>
              <p className="text-xs text-gray-500 mt-1">{template.description}</p>
            </button>
          ))}
        </div>
      </section>

      <section>
        <div className="font-semibold text-base mb-1">Or use a custom page</div>
        <p className="text-sm text-gray-500 mb-4">
          Select a published page (editable via Gutenberg) and use it as your maintenance page.
        </p>

        {pages.length === 0 && <div className="text-sm text-gray-500">No published pages found.</div>}

        {hasPreview ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {pages.map((page) => (
              <button
                key={page.id}
                className={`border rounded-lg p-3 text-left transition ${
                  selectionType === 'page' && selectedPageId === page.id
                    ? 'border-blue-500 ring-2 ring-blue-100'
                    : 'border-gray-200'
                }`}
                onClick={() => onSelectPage(page.id)}
              >
                {page.preview ? (
                  <img src={page.preview} alt={page.title} className="rounded-md w-full h-36 object-cover mb-3" />
                ) : (
                  <div className="rounded-md w-full h-36 bg-gray-100 mb-3" />
                )}
                <div className="font-medium">{page.title || '(No title)'}</div>
              </button>
            ))}
          </div>
        ) : (
          <div className="space-y-2">
            {pages.map((page) => (
              <button
                key={page.id}
                className={`w-full border rounded-lg px-3 py-2 text-left ${
                  selectionType === 'page' && selectedPageId === page.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200'
                }`}
                onClick={() => onSelectPage(page.id)}
              >
                {page.title || '(No title)'}
              </button>
            ))}
          </div>
        )}
      </section>
    </div>
  );
};
