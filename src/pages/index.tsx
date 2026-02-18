type Theme = {
  name: string;
  slug: string;
  image: string;
};

export const Index = () => {
  const themes: Theme[] = [
    {
      slug: 'coming-soon',
      name: 'Coming Soon',
      image: 'https://tailwindflex.com/storage/thumbnails/simple-coming-soon-page-3/canvas.min.webp?v=1',
    },
    {
      slug: 'maintenance',
      name: 'On Maintenance',
      image: 'https://tailwindflex.com/storage/thumbnails/maintenance-page-template/thumb_u.min.webp?v=3',
    },
  ];

  return (
    <>
      <div className="font-medium text-base">Select a theme or a custom page</div>
      <p className="text-sm text-gray-500">
        You can customize the selected theme using our wizard or your custom page using Gutenberg, Elementor, HTML or
        any page builder.
      </p>

      <div className="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        {themes.map((theme) => (
          <div className="bg-white rounded-lg p-4 m-2">
            <img src={theme.image} alt={theme.name} className="rounded-lg mb-2" />
            <div className="flex items-center justify-between">
              <div className="text-base font-medium">{theme.name}</div>
              <a href={`/maintenias?theme=${theme.slug}`} target="_blank" className="text-blue-500">
                Preview
              </a>
            </div>
          </div>
        ))}
        <div className="bg-white rounded-lg p-4 m-2">
          <div className="text-base font-semibold">Select Custom Page</div>
          <div className="text-sm text-gray-500">
            Your custom page based on Gutenberg, Elementor, HTML or any page builder
          </div>
          <div className="flex items-center justify-between">
            <a href="/maintenias?theme=custom" target="_blank" className="text-blue-500">
              Create
            </a>
          </div>
        </div>
      </div>
    </>
  );
};
