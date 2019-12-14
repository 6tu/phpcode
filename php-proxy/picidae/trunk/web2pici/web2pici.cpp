/* --------------------------------------------------------------------------

web2pici is part of the pici-server for Linux of the artproject picidae 
http://www.picidae.net
Copyright (c) 2008  picidae.net by christoph wachter and mathias jud

web2pici makes screenshots of webpages, analyzes the webpage structure 
and writes image-maps of the links as well as forms that are placed on 
the exact position of the old form.

This program is based on khtml2png2 2.6.7 from Simom MacMullen et al.
It was extended by picidae.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

-------------------------------------------------------------------------- */

#include <qpixmap.h>
#include <qimage.h>
#include <qpainter.h>
#include <qobjectlist.h>
#include <qtimer.h>
#include <qfile.h>
#include <qtextstream.h>


#include <khtml_part.h>
#include <khtmlview.h>
#include <kcmdlineargs.h>
#include <klocale.h>
#include <kaboutdata.h>

/* ------------------------------------------------------------
 * start configuration
 * ------------------------------------------------------------ */

//#include <dom/html_misc.h> //<-- use this for Suse and Mandriva
#include <kde/dom/html_misc.h> //<-- use this for other distributions

/* ------------------------------------------------------------
 * end configuration
 * ------------------------------------------------------------ */


#include "web2pici.h"





/**
 **name web2pici(const QString& path, const QString& id, int m_width, int m_height)
 **description Start a new instance
 **parameter path: URL to open
 **parameter id: ID for autodetection
 **parameter width: Width of the screenshot (if id is empty)
 **parameter height: Height of the screenshot (if id is empty)
 **/
web2pici::web2pici(const KCmdLineArgs* const args)
:KApplication(), m_html(0), pix(0)
{
	const QString width  = args->getOption("width");
	const QString height = args->getOption("height");
	autoDetectId = args->getOption("auto");
	timeoutMillis = args->getOption("time").toUInt() * 1000;

	rect = QRect(0, 0, width.isEmpty() ? -1 : width.toInt(), height.isEmpty() ? -1 : height.toInt());

	detectionCompleted = false;
	loadingCompleted   = false;
	filename           = args->arg(1);
	killPopup = !args->isSet("disable-popupkiller");
	init(args->arg(0), !args->isSet("disable-js"),
	     !args->isSet("disable-java"),
	     !args->isSet("disable-plugins"),
	     !args->isSet("disable-redirect"));
}





/**
 **name ~web2pici()
 **description: Destructor
 **/
web2pici::~web2pici()
{
	if (m_html)
	{
		delete m_html;
	}
	if (pix)
	{
		delete pix;
	}
}





/**
 **name eventFilter(QObject *o, QEvent *e)
 **description Intercept QMessageBoxes creation and delete them in order keep a non-modal interface
 **/
bool web2pici::eventFilter(QObject *o, QEvent *e)
{
	if (e->type() == QEvent::ChildInserted && killPopup)
	{
		QChildEvent *ce = (QChildEvent*)e;
		if (ce->child()->inherits("QDialog"))
		{
			o->removeChild(ce->child());
			((QDialog*)(ce->child()))->setModal(false);
			ce->child()->deleteLater();
		}
	}
	return false;
}





/**
 **name grabChildWidgets( QWidget * w )
 **description Creates a screenshot with all widgets of a window.
 **parameter w: Pointer to the window widget.
 **returns: QPixmap with the screenshot.
 **/
QPixmap *web2pici::grabChildWidgets(QWidget* w) const
{
	/*
	   This solution was taken from:
http://lists.kde.org/?l=kde-devel&m=108664293315286&w=2
*/
	w->repaint(false);
	QPixmap *res = new QPixmap(w->width(), w->height());
	if (w->rect().isEmpty())
	{
		return res;
	}
	res->fill(w, QPoint(0, 0));
	::bitBlt(res, QPoint(0, 0), w, w->rect(), Qt::CopyROP, true);

	const QObjectList *children = w->children();
	if (!children)
	{
		return res;
	}
	QPainter p(res, true);
	QObjectListIterator it(*children);
	QObject *child;
	while ((child = it.current()) != 0)
	{
		++it;
		if (child->isWidgetType()
		    && ((QWidget *)child)->geometry().intersects(w->rect())
		    && !child->inherits("QDialog"))
		{

			// those conditions aren't quite right, it's possible
			// to have a grandchild completely outside its
			// grandparent, but partially inside its parent.  no
			// point in optimizing for that.
			const QPoint childpos = ((QWidget *)child)->pos();
			const QPixmap * const cpm = grabChildWidgets( (QWidget *)child );

			if (cpm->isNull())
			{
				// Some child pixmap failed - abort and reset
				res->resize(0, 0);
				delete cpm;
				break;
			}

			p.drawPixmap(childpos, *cpm);
			p.flush();
			delete cpm;
		}
	}
	return res;
}




/**
 **name resizeClipper(const int width, const int height)
 **description Try to resize the khtmlview so that the visible area will have at least the given size
 **/
void web2pici::resizeClipper(const int width, const int height)
{

	fprintf(stderr,
		"picidae: m_html->view()->width() %i; m_html->view()->height() %i \n",
		m_html->view()->width(),
                m_html->view()->height()
                );
        
	fprintf(stderr,
		"picidae: width %i; height %i \n",
		width,
                height
                );

	fprintf(stderr,
		"picidae: m_html->view()->clipper()->width() %i; m_html->view()->clipper()->height() %i \n",
		m_html->view()->clipper()->width(),
                m_html->view()->clipper()->height()
                );

	//added by mj to correct correct view size
	xVisible = m_html->view()->clipper()->width();
	yVisible = m_html->view()->clipper()->height();
	// end added



        const int x = width + m_html->view()->width() - m_html->view()->clipper()->width();
	const int y = height + m_html->view()->height() - m_html->view()->clipper()->height();
	m_html->view()->resize(x, y);
	//m_html->view()->resize(xVisible, yVisible);


	fprintf(stderr,
		"picidae: x %i; y %i \n",
		x,
                y
                );

}




/**
 **name slotCompleted()
 **description Searches for the position of a HTML element to use as screenshot size marker or sets the m_completed variable.
 **/
void web2pici::completed()
{
	loadingCompleted = true;
	if (!detectionCompleted && !autoDetectId.isEmpty())
	{
		//search for the HTML element
		DOM::Node markerNode = m_html->htmlDocument().all().namedItem(autoDetectId);

		if (!markerNode.isNull())
		{
			//get its position
			rect = m_html->htmlDocument().all().namedItem(autoDetectId).getRect();
			if (rect.isEmpty()) {
				rect = QRect(0, 0, rect.right(), rect.bottom());
			}
			resizeClipper(rect.right() + 200, rect.bottom() + 200);
			rect = m_html->htmlDocument().all().namedItem(autoDetectId).getRect();
			if (rect.isEmpty()) {
				rect = QRect(0, 0, rect.right(), rect.bottom());
			}
			detectionCompleted = true;
		}
		else
		{
			fprintf(stderr,
				"ERROR: Can't find a HTML element with the ID \"%s\" in the current page.\n",
				autoDetectId.latin1());
			autoDetectId = QString::null;
		}
	}
	//@@@ check screen size
		
	//@@@ loop through all the elements and search for the right width
	// min width & min height
	// max width & max height
	int minW = 800;	
	int minH = 600;
	int maxW = 1195;
	//int maxH = 20000;
	int maxH = 5000;
	
	int cWidth  = minW;
	int cHeight = minH;
	//??? xCapture yCapture
	
	//@@@ div
	//@@@ img
	//@@@ table
	//@@@ p
	DOM::NodeList part = m_html->htmlDocument().getElementsByTagName("div");
	for (long i=0; i < part.length(); i++)
	{
		if (cWidth < part.item(i).getRect().right()) cWidth = part.item(i).getRect().right();
		if (cHeight < part.item(i).getRect().bottom()) cHeight = part.item(i).getRect().bottom();
	}	

	part = m_html->htmlDocument().getElementsByTagName("img");
	for (long i=0; i < part.length(); i++)
	{
		if (cWidth < part.item(i).getRect().right()) cWidth = part.item(i).getRect().right();
		if (cHeight < part.item(i).getRect().bottom()) cHeight = part.item(i).getRect().bottom();
	}	
	
	part = m_html->htmlDocument().getElementsByTagName("table");
	for (long i=0; i < part.length(); i++)
	{
		if (cWidth < part.item(i).getRect().right()) cWidth = part.item(i).getRect().right();
		if (cHeight < part.item(i).getRect().bottom()) cHeight = part.item(i).getRect().bottom();
	}	
	
	part = m_html->htmlDocument().getElementsByTagName("p");
	for (long i=0; i < part.length(); i++)
	{
		if (cWidth < part.item(i).getRect().right()) cWidth = part.item(i).getRect().right();
		if (cHeight < part.item(i).getRect().bottom()) cHeight = part.item(i).getRect().bottom();
	}	
	
	fprintf(stderr,
		"pici-canvas: %i, %i\n",
		cWidth, 
		cHeight
		);
	
	if (cWidth > maxW) cWidth = maxW;
	if (cHeight > maxH) cHeight = maxH;


	//@@@ resize window
	//resizeClipper(cWidth, cHeight);
	//??? xCapture yCapture
	//xCapture = cWidth;
	//yCapture = cHeight;
	
	rect = QRect(0, 0, cWidth, cHeight);

	//@@@ calculate height of the image
	

	// -------------------------------------------	
	// print page url
	fprintf(stderr,
		"<page>%s</page>\n",
		m_html->toplevelURL().htmlURL().ascii()
		);
/*
	// ------------------------------------------
	// analyse links
	DOM::NodeList linkNode = m_html->htmlDocument().getElementsByTagName("a");

	QString areaname = filename +".xml";
	QFile areafile( areaname );
	if ( areafile.open( IO_WriteOnly | IO_Translate ) )
	{
		fprintf(stderr,
			"pici-success: opened file %s\n",
			areaname.latin1()
			);
		QTextStream stream( &areafile );
		stream << "<map name=\"map\">" << endl;

		stream << "<page><![CDATA[";
		stream << m_html->toplevelURL().htmlURL().ascii();
		stream << "]]></page>" << endl;

		for (long i=0; i < linkNode.length(); i++)
		{
	    		stream << "<area shape=\"rect\" coords=\"";
	    		stream << linkNode.item(i).getRect().left() << ",";
	    		stream << linkNode.item(i).getRect().top() << ",";
	    		stream << linkNode.item(i).getRect().right() << ",";
	    		stream << linkNode.item(i).getRect().bottom();
	    		stream << "\" alt=\"\"><![CDATA[";

			// gives the exact content of the attribute and not the absolute URI
			stream << linkNode.item(i).attributes().getNamedItem("href").nodeValue().string().ascii();
			// the URI could be received from the htmllinkelement.
			
			stream << "]]></area>" << endl;
		}
		stream << "</map>" << endl;
		areafile.close();
	}
	else
	{
		fprintf(stderr,
			"pici-error: could not create file %s\n",
			areaname.latin1()
			);
	}

        // ---------------------------------------
	// analyse forms
	DOM::NodeList formNode = m_html->htmlDocument().getElementsByTagName("form");
	fprintf(stderr,
		"picidae: formNode.lenght %li\n",
		formNode.length()
		);

	QString formname = filename +".form.xml";
	QFile formfile( formname );
	if ( formfile.open( IO_WriteOnly | IO_Translate ) )
	{
		fprintf(stderr,
			"pici-success: opened file %s\n",
			formname.latin1()
			);
		QTextStream stream( &formfile );

		stream << "<forms>" << endl;
		stream << "<page><![CDATA[";
		stream << m_html->toplevelURL().htmlURL().ascii();
		stream << "]]></page>" << endl;
		
		for (long i=0; i < formNode.length(); i++)
		{
			stream << "<form method=\"";
			stream << formNode.item(i).attributes().getNamedItem("method").nodeValue().string().ascii();
			stream << "\"><action><![CDATA[";
			stream << formNode.item(i).attributes().getNamedItem("action").nodeValue().string().ascii();
			stream << "]]></action>" << endl;

			//DOM::NodeList inputNode = formNode.item(i).childNodes().getElementsByTagName("input");

			if (formNode.item(i).hasChildNodes())
			{
				formLoop(formNode.item(i).childNodes(), stream);
			}

			stream << "</form>" << endl;
		}
		stream << "</forms>" << endl;
		formfile.close();
	}
	else
	{
		fprintf(stderr,
			"pici-error: could not create file %s\n",
			formname.latin1()
			);
	}
*/
	
	
	

	// ---------------------------------------------------
	// end change


	doRendering();
	if (save()) {
		quit();
	}
	exit(1);
}




/*
 Recursive Loop through Nodelist to find all form fields
 */
void web2pici::formLoop(DOM::NodeList nodeList, QTextStream &stream)
{
		for (long j=0; j < nodeList.length(); j++)
		{
	    		// write input fields to file
			if (nodeList.item(j).nodeName().string() == "INPUT")
			{	
				stream << "<input";
				QString type = nodeList.item(j).attributes().getNamedItem("type").nodeValue().string();
				if (type) 
					stream << " type=\"" << type.ascii() << "\"";
				if (!nodeList.item(j).attributes().getNamedItem("name").isNull())
					stream << " name=\"" << nodeList.item(j).attributes().getNamedItem("name").nodeValue().string().ascii() << "\"";
				if (!nodeList.item(j).attributes().getNamedItem("size").isNull())
					stream << " size=\"" << nodeList.item(j).attributes().getNamedItem("size").nodeValue().string().ascii() << "\"";
				//if (type && type != "hidden")
				if (!type || type && type != "hidden")
				{
					int left = nodeList.item(j).getRect().left();
					int top = nodeList.item(j).getRect().top();
					int right = nodeList.item(j).getRect().right();
					int bottom = nodeList.item(j).getRect().bottom();

					if (type && (type == "radio" | type == "checkbox"))
					{
						left -= 3;
						top -= 3;
					}
					
					stream << " style=\"position:absolute;top:" << top; 
					stream << ";left:" << left;
					stream << ";width:" << right -left;
					stream << ";height:" << bottom -top << "\"";
				}
				if (!nodeList.item(j).attributes().getNamedItem("checked").isNull())
					stream << " checked=\"" << nodeList.item(j).attributes().getNamedItem("checked").nodeValue().string().ascii() << "\"";
				//if (!nodeList.item(j).attributes().getNamedItem("value").isNull() && type != "text" && type != "password")
				//	stream << " value=\"" << nodeList.item(j).attributes().getNamedItem("value").nodeValue().string().ascii() << "\"";
	    			stream << " alt=\"\"><![CDATA[";
				
				stream << "]]></input>" << endl;
			}
			
			// loop through child nodes
			if (nodeList.item(j).hasChildNodes())
			{
				formLoop(nodeList.item(j).childNodes(), stream);
			}
		}
}




/**
 **name openURLRequest(const KURL &url, const KParts::URLArgs & )
 **description Used to change the chosen url (needed for navigation on the page e.g. clicking on links).
 **parameter url: the URL to the HTML document
 **parameter URLArgs: standard parameter for KParts
 **/
void web2pici::openURLRequest(const KURL &url, const KParts::URLArgs & )
{
	m_html->openURL(url.url());
}





/**
 **name init(const QString& path)
 **description Creates the needed KHTMLPart object for the browser and connects signals and slots.
 **parameter path: URL to open
 **/
void web2pici::init(const QString& path, const bool js, const bool java, const bool plugins, const bool redirect)
{
	m_html = new KHTMLPart;
	m_html->view()->installEventFilter(this);

	//set some basic settings
	m_html->setJScriptEnabled(js);
	m_html->setJavaEnabled(java);
	m_html->setPluginsEnabled(plugins);
	m_html->setMetaRefreshEnabled(redirect);
	m_html->setOnlyLocalReferences(false);
	m_html->setAutoloadImages(true);
	//m_html->view()->setResizePolicy(QScrollView::Manual);
	m_html->view()->setHScrollBarMode(QScrollView::AlwaysOff);
	m_html->view()->setVScrollBarMode(QScrollView::AlwaysOff);

	//this is needed for navigation on the page e.g. clicking on links
	connect(m_html->browserExtension(),
		SIGNAL(openURLRequestDelayed(const KURL&, const KParts::URLArgs&)),this,
		SLOT(openURLRequest(const KURL&, const KParts::URLArgs&)));
	connect(m_html, SIGNAL(completed()),this,SLOT(completed()));

	//at the beginning the loading isn't completely
	loadingCompleted = false;

	//@@@ not showing the window at all
        //show the window
	m_html->view()->move(0, 0);
	//m_html->view()->showMaximized();
        m_html->view()->showNormal();
        m_html->view()->resize(1000,550);
	processEvents(200);
	xVisible = m_html->view()->clipper()->width() - 20;
	yVisible = m_html->view()->clipper()->height() - 20;

	// set a maximum time before we just snapshot whatever we've got loaded so far
	QTimer *timer = new QTimer(this);
	connect(timer, SIGNAL(timeout()), this, SLOT(completed()));
	timer->start(timeoutMillis, false);

	m_html->openURL(path);
}





/**
 **name doRendering()
 **description Take a snapshot of the browser window.
 **/
void web2pici::doRendering()
{
	int yLimit = rect.bottom();
	int xLimit = rect.right();


	fprintf(stderr,
		"picidae: rect.bottom() %i; rect.right() %i \n",
		rect.bottom(),
                rect.right()
                );



	pix = new QPixmap(rect.width(), rect.height());
	pix->fill();
	//int maxH = 20000;
	if (autoDetectId.isEmpty())
	{
/*
		if (rect.width() != m_html->view()->clipper()->width() || rect.height() != m_html->view()->clipper()->height()) {
			
			//@@@ BUG!!!: this for loop repeats eternally
			for (int i = 6000; rect.height() > m_html->view()->clipper()->height() || i>8000; i += 1000) 
			{
				if (i > rect.height())
				{
					i = rect.height();
				}
				
				m_html->view()->resize(rect.width(), i);
				processEvents(200);
				resizeClipper(rect.width(), i);
			}
		}
*/
		if (rect.width() != m_html->view()->clipper()->width() || rect.height() != m_html->view()->clipper()->height()) {
			m_html->view()->resize(rect.width(), rect.height());
			processEvents(200);
			resizeClipper(rect.width(), rect.height());
		}

		int bottom = m_html->htmlDocument().getRect().bottom();
		if (bottom < yLimit) {
			yLimit = bottom;
		}
		int right = m_html->htmlDocument().getRect().right();
		if (right < xLimit) {
			xLimit = right;
		}

	}


	xVisible = (rect.width() < xVisible ? rect.width() : xVisible);
	yVisible = (rect.height() < yVisible ? rect.height() : yVisible);
	
	const QPoint clipperPos = m_html->view()->clipper()->pos();


	// needed
	int yPos = rect.top();
	int xPos = rect.left();




	// ------------------------------------------
	// analyse links
	DOM::NodeList linkNode = m_html->htmlDocument().getElementsByTagName("a");

	QString areaname = filename +".xml";
	QFile areafile( areaname );
	if ( areafile.open( IO_WriteOnly | IO_Translate ) )
	{
		fprintf(stderr,
			"pici-success: opened file %s\n",
			areaname.latin1()
			);
		QTextStream stream( &areafile );
		stream << "<map name=\"map\">" << endl;

		stream << "<page><![CDATA[";
		stream << m_html->toplevelURL().htmlURL().ascii();
		stream << "]]></page>" << endl;

		for (long i=0; i < linkNode.length(); i++)
		{
	    		stream << "<area shape=\"rect\" coords=\"";
	    		stream << linkNode.item(i).getRect().left() << ",";
	    		stream << linkNode.item(i).getRect().top() << ",";
	    		stream << linkNode.item(i).getRect().right() << ",";
	    		stream << linkNode.item(i).getRect().bottom();
	    		stream << "\" alt=\"\"><![CDATA[";

			// gives the exact content of the attribute and not the absolute URI
			stream << linkNode.item(i).attributes().getNamedItem("href").nodeValue().string().ascii();
			// the URI could be received from the htmllinkelement.
			
			stream << "]]></area>" << endl;
		}
		stream << "</map>" << endl;
		areafile.close();
	}
	else
	{
		fprintf(stderr,
			"pici-error: could not create file %s\n",
			areaname.latin1()
			);
	}

        // ---------------------------------------
	// analyse forms
	DOM::NodeList formNode = m_html->htmlDocument().getElementsByTagName("form");
	fprintf(stderr,
		"picidae: formNode.lenght %li\n",
		formNode.length()
		);

	QString formname = filename +".form.xml";
	QFile formfile( formname );
	if ( formfile.open( IO_WriteOnly | IO_Translate ) )
	{
		fprintf(stderr,
			"pici-success: opened file %s\n",
			formname.latin1()
			);
		QTextStream stream( &formfile );

		stream << "<forms>" << endl;
		stream << "<page><![CDATA[";
		stream << m_html->toplevelURL().htmlURL().ascii();
		stream << "]]></page>" << endl;
		
		for (long i=0; i < formNode.length(); i++)
		{
			stream << "<form method=\"";
			stream << formNode.item(i).attributes().getNamedItem("method").nodeValue().string().ascii();
			stream << "\"><action><![CDATA[";
			stream << formNode.item(i).attributes().getNamedItem("action").nodeValue().string().ascii();
			stream << "]]></action>" << endl;

			//DOM::NodeList inputNode = formNode.item(i).childNodes().getElementsByTagName("input");

			if (formNode.item(i).hasChildNodes())
			{
				formLoop(formNode.item(i).childNodes(), stream);
			}

			stream << "</form>" << endl;
		}
		stream << "</forms>" << endl;
		formfile.close();
	}
	else
	{
		fprintf(stderr,
			"pici-error: could not create file %s\n",
			formname.latin1()
			);
	}
	
	// end page analyzation
        // ---------------------------------------

			//capture the part of the screen
			const QPixmap* const temp = grabChildWidgets(m_html->view()->clipper());
			QRect pos = temp->rect();

			pos.setLeft(pos.left() + xPos);
			pos.setTop(pos.top() + yPos);
			
			int yPosB = yPos;
			if (yPos + yVisible > m_html->view()->contentsHeight()) yPosB = m_html->view()->contentsHeight() - yVisible;


			//::bitBlt(pix, QPoint(xPos - rect.left(), yPos - rect.top()), temp, pos, Qt::CopyROP, true);
			::bitBlt(pix, 
				QPoint(xPos - rect.left(), yPosB - rect.top()), 
				temp, 
				temp->rect(), 
				Qt::CopyROP, 
				true);

			delete temp;

		// was this enough?
		if (rect.height() > m_html->view()->clipper()->height())
		{
			

			m_html->view()->setContentsPos(0, clipperPos.y());
			processEvents(200);
			m_html->view()->move(0, clipperPos.y());
			processEvents(200);
			resizeClipper(rect.width(), clipperPos.y());
			processEvents(200);
			m_html->view()->repaint();
			processEvents(200);
			//processEvents(1000);

			//capture the part of the screen
			const QPixmap* const temp = grabChildWidgets(m_html->view()->clipper());
			QRect pos = temp->rect();

			pos.setLeft(pos.left() + xPos);
			pos.setTop(pos.top() + yPos);
			
			int yPosB = yPos;
			if (yPos + yVisible > m_html->view()->contentsHeight()) yPosB = m_html->view()->contentsHeight() - yVisible;


			//::bitBlt(pix, QPoint(xPos - rect.left(), yPos - rect.top()), temp, pos, Qt::CopyROP, true);
			::bitBlt(pix, 
				QPoint(xPos - rect.left(), yPosB - rect.top()), 
				temp, 
				temp->rect(), 
				Qt::CopyROP, 
				true);

			delete temp;
		}



/*
//	for (int yPos = rect.top() ; yPos <= yLimit ; yPos += yVisible)
//	{
//		for (int xPos = rect.left() ; xPos <= xLimit ; xPos += xVisible)
//		{
	    	    fprintf(stderr,
			"pici-part: xPos %i, yPos %i; move to %i, %i \n",
			xPos,
                	yPos,
			xPos + clipperPos.x(),
			yPos + clipperPos.y()
                	);
			
			
			//m_html->view()->setContentsPos(xPos + clipperPos.x(), yPos + clipperPos.y());
			//m_html->view()->move(-xPos - clipperPos.x(), -yPos - clipperPos.y());
			//m_html->view()->repaint();
			//processEvents(200);
			//processEvents(1000);

			//capture the part of the screen
			const QPixmap* const temp = grabChildWidgets(m_html->view()->clipper());
			QRect pos = temp->rect();

	    	    fprintf(stderr,
			"clipper-rect: %i, %i, %i, %i \n",
			pos.left(),
                	pos.top(),
			pos.right(),
			pos.bottom()
                	);
			
			pos.setLeft(pos.left() + xPos);
			pos.setTop(pos.top() + yPos);
			
			int yPosB = yPos;
			if (yPos + yVisible > m_html->view()->contentsHeight()) yPosB = m_html->view()->contentsHeight() - yVisible;


		     fprintf(stderr,
			"yPos: %i, yPosB %i, yVisible %i, visibleHeight %i, %i, %i \n",
			yPos,
                	yPosB,
			yVisible,
			m_html->view()->visibleHeight(),
			m_html->view()->contentsHeight(),
			m_html->htmlDocument().getRect().bottom()
                	);


			//::bitBlt(pix, QPoint(xPos - rect.left(), yPos - rect.top()), temp, pos, Qt::CopyROP, true);
			::bitBlt(pix, 
				QPoint(xPos - rect.left(), yPosB - rect.top()), 
				temp, 
				temp->rect(), 
				Qt::CopyROP, 
				true);

			delete temp;
//		}
//	}
*/

}





/**
 **name save(const QString& file)
 **description Save the snapshot in a file
 **parameter file: filename
 **returns true, if the saving was sucessfully otherwise false.
 **/
bool web2pici::save() const
{
	//QString format = filename.section('.', -1).stripWhiteSpace().upper();
	QString format = "PNG";
	if (format == "JPG" || format == "JPE")
	{
		format = "JPEG";
	}
	return pix->convertToImage().save(filename +".png", format);
}





/**
 **name options
 **description Array with command line options and descriptions
 **/
static KCmdLineOptions options[] =
{
	{ "w", 0, 0},
	{ "width <width>", "Width of canvas on which to render html", "1000" },
	{ "h", 0, 0},
	{ "height <height>", "Height of canvas on which to render html", "1000" },
	{ "t", 0, 0},
	{ "time <time>", "Maximum time in seconds to spend loading page", "30" },
	{ "auto <id>", "Use this option if you to autodetect the bottom/right border", "" },
	{ "disable-js", "Enable/Disable javascript (enabled by default)", 0 },
	{ "disable-java", "Enable/Disable java (enabled by default)", 0},
	{ "disable-plugins", "Enable/Disable KHTML plugins (like Flash player, enabled by default)", 0},
	{ "disable-redirect", "Enable/Disable auto-redirect by header <meta > (enabled by default)", 0},
	{ "disable-popupkiller", "Enable/Disable popup auto-kill (enabled by default)", 0},
	{ "+url ", "URL of page to render", 0 },
	{ "+outfile ", "Output file", 0 },
	{ 0, 0, 0 }
};





int main(int argc, char **argv)
{
	KAboutData aboutData("web2pici", I18N_NOOP("web2pici"), "1.0",
			     I18N_NOOP("Render webpages to pici\n\
				       Example:\n\
				       web2pici http://www.picidae.net test.png\n\
				       or\n\
				       web2pici --auto ID_border http://www.kde.org/ test.png"),
			     KAboutData::License_GPL,
			     "(c) 2007 picidae.net");
	KCmdLineArgs::init(argc, argv, &aboutData);
	KCmdLineArgs::addCmdLineOptions(options);
	KCmdLineArgs *args = KCmdLineArgs::parsedArgs();

	if (args->count() < 2)
	{
		args->usage();
		exit(1);
	}

	KInstance inst(&aboutData);
	web2pici app(args);
	app.exec(); 
}

#include "web2pici.moc"

