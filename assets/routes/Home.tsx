import React, { useEffect } from 'react'
import { useTranslation } from 'react-i18next';

export default function Home() {

    const { t } = useTranslation()

    useEffect(() => {
        document.title = `Sociant Hub - ${ t('pageTitles.home') }`;
    }, [])

    return (
        <div>Homesdf</div>
    )
}