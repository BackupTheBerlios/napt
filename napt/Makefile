###
# Global Vars
###
PROJECT=napt_siesaso

###
# Alle temporaeren Dateien entfernen, die nur stoeren
###
clean:
    #Entfernt alle temporären Dateien:
	@rm -f `find ./ -name "*~" -xtype f`
	@rm -Rf documentation doc

###
# Alle Dateien entfernen, die nur beim Entwickeln benoetigt werden
###
webclean:
	@rm -Rf `find ./ -name "CVS" -xtype d`
	@rm -f  `find ./ -name "*~" -xtype f`
	@rm -f  `find ./ -name ".*" -xtype f`
	@rm -Rf `find ./ -name ".[a-zA-Z0-9]*"`

