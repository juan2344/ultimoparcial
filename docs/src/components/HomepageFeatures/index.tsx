import type { ComponentProps, ComponentType, ReactNode } from 'react'
import clsx from 'clsx'
import Heading from '@theme/Heading'
import styles from './styles.module.css'

type FeatureItem = {
  title: string
  Svg: ComponentType<ComponentProps<'svg'>>
  description: ReactNode
}

const FeatureList: FeatureItem[] = [
  {
    title: 'Rápido de instalar y comenzar a usar.',
    Svg: require('@site/static/img/undraw_docusaurus_mountain.svg').default,
    description: <>Rápido de instalar y comenzar a usar.</>,
  },
  {
    title: 'Concéntrate en tu documentación, nosotros hacemos el resto.',
    Svg: require('@site/static/img/undraw_docusaurus_tree.svg').default,
    description: <>Concéntrate en tu documentación, nosotros hacemos el resto.</>,
  },
  {
    title: 'Extiende o personaliza tu web reutilizando React.',
    Svg: require('@site/static/img/undraw_docusaurus_react.svg').default,
    description: <>Extiende o personaliza tu web reutilizando React.</>,
  },
]

function Feature({ title, Svg, description }: FeatureItem) {
  return (
    <div className={clsx('col col--4')}>
      <div className="text--center">
        <Svg className={styles.featureSvg} role="img" />
      </div>
      <div className="text--center padding-horiz--md">
        <Heading as="h3">{title}</Heading>
        <p>{description}</p>
      </div>
    </div>
  )
}

export default function HomepageFeatures(): ReactNode {
  return (
    <section className={styles.features}>
      <div className="container">
        <div className="row">
          {FeatureList.map((props, idx) => (
            <Feature key={idx} {...props} />
          ))}
        </div>
      </div>
    </section>
  )
}
