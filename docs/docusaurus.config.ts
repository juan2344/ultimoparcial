import { themes as prismThemes } from 'prism-react-renderer'
import type { Config } from '@docusaurus/types'
import type * as Preset from '@docusaurus/preset-classic'

// Configuración principal de Docusaurus
const config: Config = {
  title: 'Ultimo Parcial',
  tagline: 'Proyecto de documentación',
  favicon: '../img/favicon.ico',

  future: { v4: true },

  // Producción en GitHub Pages
  url: 'https://juan2344.github.io',
  baseUrl: '/ultimoparcial/',

  // Configuración de GitHub Pages
  organizationName: 'juan2344',
  projectName: 'ultimoparcial',

  onBrokenLinks: 'throw',

  // Internacionalización
  i18n: { defaultLocale: 'en', locales: ['en'] },

  // Presets
  presets: [
    [
      'classic',
      {
        docs: {
          sidebarPath: require.resolve('./sidebars.ts'),
          editUrl: 'https://github.com/juan2344/ultimoparcial/edit/main/',
        },
        blog: {
          showReadingTime: true,
          feedOptions: { type: ['rss', 'atom'], xslt: true },
          editUrl: 'https://github.com/juan2344/ultimoparcial/edit/main/',
          onInlineTags: 'warn',
          onInlineAuthors: 'warn',
          onUntruncatedBlogPosts: 'warn',
        },
        theme: { customCss: require.resolve('./src/css/custom.css') },
      } satisfies Preset.Options,
    ],
  ],

  // Configuración del tema
  themeConfig: {
    image: '/img/docusaurus-social-card.jpg',
    colorMode: { respectPrefersColorScheme: true },
    navbar: {
      title: 'Ultimo Parcial',
      logo: { alt: 'Ultimo Parcial Logo', src: '/img/logo.svg' },
      items: [
        { type: 'docSidebar', sidebarId: 'tutorialSidebar', position: 'left', label: 'Tutorial' },
        { href: 'https://github.com/juan2344/ultimoparcial', label: 'GitHub', position: 'right' },
      ],
    },
    footer: {
      style: 'dark',
      links: [
        {
          title: 'More',
          items: [{ label: 'GitHub', href: 'https://github.com/juan2344/ultimoparcial' }],
        },
      ],
      copyright: `Copyright © ${new Date().getFullYear()} Kevin Villegas. Built with Docusaurus.`,
    },
    prism: {
      theme: prismThemes.github,
      darkTheme: prismThemes.dracula,
    },
  } satisfies Preset.ThemeConfig,
}

export default config
