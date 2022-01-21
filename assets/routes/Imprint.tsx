import React, { useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import { Container } from '../styledComponents/globalStyles'
import { LegalPage } from '../styledComponents/legalStyles'

export default function Imprint() {
	const { t } = useTranslation()

	useEffect(() => {
		document.title = `Sociant Hub - ${t('pageTitles.imprint')}`
	}, [])

	return (
		<LegalPage>
			<Container>
				<h1 className="page-title">Legal Disclosure</h1>
				Information in accordance with Section 5 TMG
				<br />
				<br />
				Sociant Web-Development
				<br />
				Inh. Luca Voges
				<br />
				Donaustraße 17
				<br />
				99089 Erfurt
				<br />
				<h2>Contact Information</h2>
				Telephone: 017634517371
				<br />
				E-Mail: <a href="mailto:info@sociant.de">info@sociant.de</a>
				<br />
				Internet address:{' '}
				<a href="https://hub.sociant.de" target="_blank">
					https://hub.sociant.de
				</a>
				<br />
				<h2>Graphics and Image Sources</h2>
				Twitter.com
				<br />
				<a href="https://de.freepik.com/fotos-vektoren-kostenlos/technologie">
					Technologie Vektor erstellt von macrovector - de.freepik.com
				</a>
				<br />
				<br />
				<h2>Disclaimer</h2>
				Accountability for content
				<br />
				The contents of our pages have been created with the utmost care. However, we cannot guarantee the
				contents' accuracy, completeness or topicality. According to statutory provisions, we are furthermore
				responsible for our own content on these web pages. In this matter, please note that we are not obliged
				to monitor the transmitted or saved information of third parties, or investigate circumstances pointing
				to illegal activity. Our obligations to remove or block the use of information under generally
				applicable laws remain unaffected by this as per §§ 8 to 10 of the Telemedia Act (TMG).
				<br />
				<br />
				Accountability for links
				<br />
				Responsibility for the content of external links (to web pages of third parties) lies solely with the
				operators of the linked pages. No violations were evident to us at the time of linking. Should any legal
				infringement become known to us, we will remove the respective link immediately.
				<br />
				<br />
				Copyright
				<br /> Our web pages and their contents are subject to German copyright law. Unless expressly permitted
				by law, every form of utilizing, reproducing or processing works subject to copyright protection on our
				web pages requires the prior consent of the respective owner of the rights. Individual reproductions of
				a work are only allowed for private use. The materials from these pages are copyrighted and any
				unauthorized use may violate copyright laws.
				<br />
				<br />
				<i>Quelle: </i>
				<a href="http://www.translate-24h.de" target="_blank">
					Übersetzungsdienst translate-24h.de
				</a>{' '}
				<br />
				<br />
			</Container>
		</LegalPage>
	)
}
