export type PageItem = {
  id: number;
  title: string;
  permalink: string;
};

export type TemplateItem = {
  slug: string;
  name: string;
  description: string;
  previewUrl: string;
  previewImage: string;
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

import { useState } from '@wordpress/element';

export const Index = ({
  pages,
  templates,
  selectionType,
  selectedPageId,
  selectedTemplateSlug,
  onSelectPage,
  onSelectTemplate,
}: Props) => {
  const [pendingPageId, setPendingPageId] = useState<number>(selectedPageId);
  const canSave = pendingPageId > 0 && (pendingPageId !== selectedPageId || selectionType !== 'page');

  return (
    <div className="space-y-8">
      <section>
        <div className="font-semibold text-base mb-1">Built-in maintenance templates</div>
        <p className="text-sm text-gray-500 mb-4">Choose one of the ready-made designs for instant setup.</p>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {templates.map((template) => (
            <div
              key={template.slug}
              className={`border rounded-lg p-3 transition ${
                selectionType === 'template' && selectedTemplateSlug === template.slug
                  ? 'border-blue-500 ring-2 ring-blue-200'
                  : 'border-gray-200 hover:border-gray-300'
              }`}
            >
              <button
                type="button"
                className="w-full text-left"
                onClick={() => onSelectTemplate(template.slug)}
              >
                <img
                  src={template.previewImage}
                  alt={template.name}
                  className="rounded-md w-full mb-3 bg-gray-100"
                />
                <div className="font-medium">{template.name}</div>
                <p className="text-xs text-gray-500 mt-1">{template.description}</p>
              </button>
              <a
                href={template.previewUrl}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-block mt-2 text-xs text-blue-600 hover:text-blue-800"
              >
                Preview &rarr;
              </a>
            </div>
          ))}
        </div>
      </section>

      <section>
        <div className="font-semibold text-base mb-1">Or use a custom page</div>
        <p className="text-sm text-gray-500 mb-4">
          Select a published page (editable via Gutenberg) and use it as your maintenance page.
        </p>

        {pages.length === 0 ? (
          <p className="text-sm text-gray-500">No published pages found.</p>
        ) : (
          <div className="flex gap-3 max-w-md">
            <select
              className="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
              value={pendingPageId}
              onChange={(e) => setPendingPageId(Number(e.target.value))}
            >
              <option value={0}>Select a page</option>
              {pages.map((page) => (
                <option key={page.id} value={page.id}>
                  {page.title || '(No title)'}
                </option>
              ))}
            </select>
            <button
              type="button"
              disabled={!canSave}
              onClick={() => onSelectPage(pendingPageId)}
              className={`rounded-lg px-5 py-3 text-sm font-medium transition ${
                canSave
                  ? 'bg-blue-600 text-white hover:bg-blue-700'
                  : 'bg-gray-100 text-gray-400 cursor-not-allowed'
              }`}
            >
              Save
            </button>
          </div>
        )}
      </section>
    </div>
  );
};
