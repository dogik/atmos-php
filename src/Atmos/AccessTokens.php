<?php
namespace Atmos;

use \DOMDocument;

class AccessToken extends AccessTokenType {
	/**
	 * Builds an AccessToken object from an XML string.
	 * @param string $data
	 */
	public static function fromXml( $data ) {
		$dom = new DOMDocument( );
		$parseOk = false;
		$parseOk = $dom->loadXML( $data );
		if( !$parseOk ) {
			throw new EsuException('Failed to parse Policy XML');
		}
		
		$accessToken = new AccessToken();
		
		$root = $dom->firstChild;
		
		if($root->localName != 'access-token' ) {
			throw new EsuException('Expected root node to be \'access-token\'.  It was ' . $root->localName);
		}
		
		$accessToken->loadFromElement( $root );
		
		return $accessToken;
	}
	
} // end class access-token

class AccessTokensListType
{


	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName access-token
	 * @var AccessTokenType[]
	 */
	public $accessToken;

	/**
	 * Loads this object from a DOMElement
	 * @param DOMElement $element
	 */
	public function loadFromElement( $element ) {
		$children = $element->childNodes;
	
		for($i=0; $i<$children->length; $i++) {
			$child = $children->item($i);
			if($child->nodeType != XML_ELEMENT_NODE) {
				continue;
			}
				
			$localName = $child->localName;
			print("Local Name: $localName \n");
				
			if($localName == 'access-token') {
				$accessToken = new AccessTokenType();
				$accessToken->loadFromElement($child);
				$this->accessToken[] = $accessToken;
			}
		}
	}
	
} // end class accessTokensListType

/**
 * @xmlType
 * @xmlName accessTokenType
 * @var org\example\www\policy\accessTokenType
 */
class AccessTokenType {


	/**
	 * @xmlType element
	 * @xmlMinOccurs 1
	 * @xmlMaxOccurs 1
	 * @xmlName access-token-id
	 * @var string
	 */
	public $accessTokenId;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName expiration
	 * @var dateTime
	 */
	public $expiration;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName max-uploads
	 * @var int
	 */

	public $maxUploads;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName max-downloads
	 * @var int
	 */
	public $maxDownloads;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName source
	 * @var SourceType
	 */
	public $source;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 1
	 * @xmlMaxOccurs 1
	 * @xmlName content-length-range
	 * @var ContentLengthRangeType
	 */
	public $contentLengthRange;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName uid
	 * @var string
	 */
	public $uid;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName object-id
	 * @var string
	 */
	public $objectId;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName path
	 * @var string
	 */
	public $path;
	
	
	/**
	 * Loads this object from a DOMElement
	 * @param DOMElement $element
	 */
	public function loadFromElement( $element ) {
		$children = $element->childNodes;
	
		for($i=0; $i<$children->length; $i++) {
			$child = $children->item($i);
			if($child->nodeType != XML_ELEMENT_NODE) {
				continue;
			}
				
			$localName = $child->localName;
			print("Local Name: $localName \n");
				
			if($localName == 'access-token-id') {
				$this->accessTokenId = $child->nodeValue;
			} else if($localName == 'expiration') {
				$this->expiration = strtotime($child->nodeValue);
			} else if($localName == 'max-uploads') {
				$this->maxUploads = intval($child->nodeValue, 10);
			} else if($localName == 'max-downloads') {
				$this->maxDownloads = intval($child->nodeValue, 10);
			} else if($localName == 'source') {
				$this->source = new SourceType();
				$this->source->loadFromElement($child);
			} else if($localName == 'content-length-range') {
				$this->contentLengthRange = new ContentLengthRangeType();
				$this->contentLengthRange->loadFromElement($child);
			} else if($localName == 'uid') {
				$this->uid = $child->nodeValue;
			} else if($localName == 'object-id') {
				$this->objectId = $child->nodeValue;
			} else if($localName == 'path') {
				$this->path = $child->nodeValue;
			}
			
		}
	}
	
} // end class accessTokenType


/**
 * @xmlType
 * @xmlName contentLengthRangeType
 * @var ContentLengthRangeType
 */
class ContentLengthRangeType {

	/**
	 * @xmlType attribute
	 * @xmlName from
	 * @var int
	 */
	public $from;

	/**
	 * @xmlType attribute
	 * @xmlName to
	 * @var int
	 */
	public $to;

	/**
	 * Populates this object from a DOMElement
	 * @param DOMElement $element
	 */
	public function loadFromElement($element) {
		$this->from = $element->getAttribute('from');
		$this->to = $element->getAttribute('to');
	}
	
	/**
	 * Writes this object to a DOMElement
	 * @param DOMElement $element the element to populate
	 * @param DOMDocument $doc the document to create nodes under
	 */
	public function writeToElement($element, $doc) {
		$element->setAttribute('from', $this->from);
		$element->setAttribute('to', $this->to);
	}
} // end class ContentLengthRangeType


/**
 * @xmlType
 * @xmlName formFieldType
 * @var FormFieldType
 */
class FormFieldType {


	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName matches
	 * @var string[]
	 */
	public $matches;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName eq
	 * @var string[]
	 */
	public $eq;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName starts-with
	 * @var string[]
	 */
	public $startsWith;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ends-with
	 * @var string[]
	 */
	public $endsWith;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName contains
	 * @var string[]
	 */
	public $contains;

	/**
	 * @xmlType attribute
	 * @xmlName name
	 * @var string
	 */
	public $name;

	/**
	 * @xmlType attribute
	 * @xmlName optional
	 * @var boolean
	 */
	public $optional;

	/**
	 * 
	 * @param DOMElement $element
	 */
	public function loadFromElement($element) {
		$this->name = $element->getAttribute('name');
		if($element->getAttribute('optional') == 'true') {
			$this->optional = True;
		} else if($element->getAttribute('optional') == 'false') {
			$this->optional == False;
		}
		
		$children = $element->childNodes;
		
		for($i=0; $i<$children->length; $i++) {
			$child = $children->item($i);
			if($child->nodeType != XML_ELEMENT_NODE) {
				continue;
			}
				
			$localName = $child->localName;
			print("Local Name: $localName \n");
				
			if($localName == 'matches') {
				$this->matches[] = $child->nodeValue;
			} else if($localName == 'eq') {
				$this->eq[] = $child->nodeValue;
			} else if($localName == 'starts-with') {
				$this->startsWith = $child->nodeValue;
			} else if($localName == 'ends-with') {
				$this->endsWith[] = $child->nodeValue;
			} else if($localName == 'contains') {
				$this->contains[] = $child->nodeValue;
			}
		}
	}
	
	/**
	 * Writes this object to a DOMElement
	 * @param DOMElement $element the element to populate
	 * @param DOMDocument $doc the document to create nodes under
	 */
	public function writeToElement($element, $doc) {
		$element->setAttribute('name', $this->name);
		if($this->optional) {
			$element->setAttribute('optional', $this->optional?'true':'false');
		}
		
		if($this->matches != NULL) {
			for($i=0; $i<count($this->matches); $i++) {
				$element->appendChild($doc->createElement('matches', $this->matches[$i]));
			}
		}
		if($this->eq != NULL) {
			for($i=0; $i<count($this->eq); $i++) {
				$element->appendChild($doc->createElement('eq', $this->eq[$i]));
			}
		}
		if($this->startsWith != NULL) {
			for($i=0; $i<count($this->startsWith); $i++) {
				$element->appendChild($doc->createElement('starts-with', $this->startsWith[$i]));
			}
		}
		if($this->endsWith != NULL) {
			for($i=0; $i<count($this->endsWith); $i++) {
				$element->appendChild($doc->createElement('ends-with', $this->endsWith[$i]));
			}
		}
		if($this->contains != NULL) {
			for($i=0; $i<count($this->contains); $i++) {
				$element->appendChild($doc->createElement('contains', $this->contains[$i]));
			}
		}		
	}
} // end class formFieldType

/**
 * @xmlType
 * @xmlName listAccessTokenResultType
 * @var ListAccessTokenResultType
 */
class ListAccessTokenResultType {


	/**
	 * @xmlType element
	 * @xmlMinOccurs 1
	 * @xmlMaxOccurs 1
	 * @xmlName access-tokens-list
	 * @var AccessTokensListType
	 */
	public $accessTokensList;
	
	/**
	 * Loads this object from a DOMElement
	 * @param DOMElement $element
	 */
	public function loadFromElement( $element ) {
		$children = $element->childNodes;
	
		for($i=0; $i<$children->length; $i++) {
			$child = $children->item($i);
			if($child->nodeType != XML_ELEMENT_NODE) {
				continue;
			}
				
			$localName = $child->localName;
			print("Local Name: $localName \n");
				
			if($localName == 'access-tokens-list') {
				$this->accessTokensList = new AccessTokensListType();
				$this->accessTokensList->loadFromElement($child);
			}
		}
	}


} // end class listAccessTokenResultType


/**
 * @xmlType ListAccessTokenResultType
 * @xmlName list-access-tokens-result
 * @var org\example\www\policy\list-access-tokens-result
 */
class ListAccessTokensResult extends ListAccessTokenResultType {
	/**
	 * If not NULL, there are more results to fetch.  Call listAccessTokens
	 * again with this value to continue the listing.
	 * @var string
	 */
	public $paginationToken;

	/**
	 * Builds an ListAccessTokensResult object from an XML string.
	 * @param string $data
	 */
	public static function fromXml( $data ) {
		$dom = new DOMDocument( );
		$parseOk = false;
		$parseOk = $dom->loadXML( $data );
		if( !$parseOk ) {
			throw new EsuException('Failed to parse Policy XML');
		}
	
		$listAccessTokensResult = new ListAccessTokensResult();
	
		$root = $dom->firstChild;
	
		if($root->localName != 'list-access-tokens-result' ) {
			throw new EsuException('Expected root node to be \'list-access-tokens-result\'.  It was ' . $root->localName);
		}
	
		$listAccessTokensResult->loadFromElement( $root );
	
		return $listAccessTokensResult;
	}
	

} // end class ListAccessTokensResult

/**
 * @xmlType policyType
 * @xmlName policy
 * @var Policy
 */
class Policy extends PolicyType {

	public function toXml() {
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = True;
		
		// Add the root node
		$root = $doc->createElement('policy');
		$doc->appendChild($root);
		
		$this->writeToElement($root, $doc);
		
		return $doc->saveXML();
	}
	
	public static function fromXml( $data ) {
		$doc = new DOMDocument( );
		$parseOk = false;
		$parseOk = $doc->loadXML( $data );
		if( !$parseOk ) {
			throw new EsuException('Failed to parse Policy XML');
		}
		
		$policy = new Policy();
		
		$root = $doc->firstChild;
		
		if($root->localName != 'policy' ) {
			throw new EsuException('Expected root node to be \'policy\'.  It was ' . $root->localName);
		}
		
		$policy->loadFromElement( $root );
		
		return $policy;
	}

} // end class policy

/**
 * @xmlType
 * @xmlName policyType
 * @var PolicyType
 */
class PolicyType {

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName expiration
	 * @var dateTime
	 */
	public $expiration;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName max-uploads
	 * @var int
	 */
	public $maxUploads;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName max-downloads
	 * @var int
	 */
	public $maxDownloads;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName source
	 * @var sourceType
	 */
	public $source;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 1
	 * @xmlName content-length-range
	 * @var ContentLengthRangeType
	 */
	public $contentLengthRange;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName form-field
	 * @var FormFieldType[]
	 */
	public $formField;

	/**
	 * Loads this object from a DOMElement
	 * @param DOMElement $element
	 */
	public function loadFromElement( $element ) {
		$children = $element->childNodes;
		
		for($i=0; $i<$children->length; $i++) {
			$child = $children->item($i);
			if($child->nodeType != XML_ELEMENT_NODE) {
				continue;
			}
			
			$localName = $child->localName;
			print("Local Name: $localName \n");
			
			if($localName == 'expiration') {
				$this->expiration = strtotime($child->nodeValue);
			} else if($localName == 'max-uploads') {
				$this->maxUploads = intval($child->nodeValue, 10);
			} else if($localName == 'max-downloads') {
				$this->maxDownloads = intval($child->nodeValue, 10);
			} else if($localName == 'source') {
				$this->source = new SourceType();
				$this->source->loadFromElement($child);
			} else if($localName == 'content-length-range') {
				$this->contentLengthRange = new ContentLengthRangeType();
				$this->contentLengthRange->loadFromElement($child);
			} else if($localName == 'form-field') {
				$field = new FormFieldType();
				$field->loadFromElement($child);
				$this->formField[] = $field;
			}
		}
	}
	
	/**
	 * Writes this object to a DOMElement
	 * @param DOMElement $element the element to populate
	 * @param DOMDocument $doc the document to create nodes under
	 */
	public function writeToElement($element, $doc) {
		if($this->expiration != NULL) {
			$element->appendChild($doc->createElement('expiration', gmdate("c", $this->expiration)));
		}
		if($this->maxUploads != NULL) {
			$element->appendChild($doc->createElement('max-uploads', $this->maxUploads));
		}
		if($this->maxDownloads != NULL) {
			$element->appendChild($doc->createElement('max-downloads', $this->maxDownloads));
		}
		if($this->source != NULL) {
			$sourceElement = $doc->createElement('source');
			$this->source->writeToElement($sourceElement, $doc);
			$element->appendChild($sourceElement);
		}
		if($this->contentLengthRange != NULL) {
			$contentLengthRangeElement = $doc->createElement('content-length-range');
			$this->contentLengthRange->writeToElement($contentLengthRangeElement, $doc);
			$element->appendChild($contentLengthRangeElement);
		}
		if($this->formField != NULL) {
			for($i=0; $i<count($this->formField); $i++) {
				$formFieldElement = $doc->createElement('form-field');
				$this->formField[$i]->writeToElement($formFieldElement, $doc);
				$element->appendChild($formFieldElement);
			}
		}
	}
} // end class PolicyType

/**
 * @xmlType
 * @xmlName sourceType
 * @var SourceType
 */
class SourceType {


	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName allow
	 * @var string[]
	 */
	public $allow;

	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName disallow
	 * @var string[]
	 */
	public $disallow;

	/**
	 * Populates the object from the element
	 * @param DOMElement $element
	 */
	public function loadFromElement( $element ) {
		$children = $element->childNodes;
	
		for($i=0; $i<$children->length; $i++) {
			$child = $children->item($i);
			if($child->nodeType != XML_ELEMENT_NODE) {
				continue;
			}
				
			$localName = $child->localName;
			print("Local Name: $localName \n");
			
			if($localName == 'allow') {
				$this->allow[] = $child->nodeValue;
			} else if($localName == 'disallow') {
				$this->disallow[] = $child->nodeValue;
			}
		}
	}
	
	/**
	 * Populates the given element with this object's contents.
	 * @param DOMElement $element the element to populate
	 * @param DOMDocument $doc the document for creating nodes.
	 */
	public function writeToElement($element, $doc) {
		if($this->allow != NULL) {
			for($i=0; $i<count($this->allow); $i++) {
				$element->appendChild($doc->createElement('allow', $this->allow[$i]));
			}
		}
		if($this->disallow != NULL) {
			for($i=0; $i<count($this->disallow); $i++) {
				$element->appendChild($doc->createElement('disallow', $this->disallow[$i]));
			}
		}
	}
} // end class sourceType



?>