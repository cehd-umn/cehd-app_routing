$ ->
  featurebox = new FeatureBox

class window.FeatureBox
  #feed_url: '/CI/attributes/js/feed.xml'
  # Getting a "XMLHttpRequest cannot load http://blog.lib.umn.edu/cehd/news/curriculumandinstruction.xml. Origin http://drydock.cehd.umn.edu is not allowed by Access-Control-Allow-Origin. "
  feed_url: 'http://'+window.location.hostname+'/attributes/rssParser/cirssfeed.asp'
  fadeInterval: 7

  stories: []

  constructor: ->
    @drawTable()
    @loadStories()

  drawTable: ->
    $('#featurebox').html(@tableTemplate)

  loadStories: ->
    $.ajax
      url: @feed_url
      type: 'get'
      #dataType: 'xml'
      success: (response) =>
        stories_xml = $(response).find('item')
        for story in stories_xml
          @stories.push {
            title: $(story).find('title').text()
            summary: $(story).find('summary').text()
            image: $(story).find('enclosure').attr('url')
            link: $(story).find('guid').text()
          }
        @renderStories()
      error: (response) ->
        console.log response
        console.log "Error loading XML Feed: #{response.statusText}"

  renderStories: ->
    $('#featurebox td').each (index, cell) =>
      story_html = @cellTemplate.replace /{{title}}/g, @stories[index].title
      story_html = story_html.replace /{{link}}/g, @stories[index].link
      $(cell).html($(story_html))
      $(cell).find('.story-image').css('background-image', "url(#{@stories[index].image})")
      $('#featurebox .story').hide()
    @bindStoryHover()
    @fadeRandomStories()
    @startStoryFading()

  bindStoryHover: ->
    $('#featurebox td').hover ->
      $(@).find('.story').fadeIn('fast')
    , ->
      unless $(@).find('.active').size() > 0
        $(@).find('.story').fadeOut('fast')

  startStoryFading: ->
    setInterval (=>
      @fadeRandomStories()
    ), (@fadeInterval * 1000)
    
  fadeRandomStories: ->
    $('#featurebox .story').fadeOut('fast')
    $('#featurebox .story').removeClass('active')
    @_fadeRandomStories()

  _fadeRandomStories: ->
    indexes = @shuffleArray [0, 1, 2, 3, 4, 5]
    for index in indexes[0..2]
      random_story = $('#featurebox .story')[index]
      $(random_story).fadeIn('slow')
      $(random_story).addClass('active')

  shuffleArray: (o)->
    j = undefined
    x = undefined
    i = o.length
    while i
      j = parseInt(Math.random() * i)
      x = o[--i]
      o[i] = o[j]
      o[j] = x
    return o

  tableTemplate:  '<table>' +
                    '<tbody>' +
                      '<tr><td></td><td></td><td></td></tr>' +
                      '<tr><td></td><td></td><td></td></tr>' +
                    '</tbody>' +
                  '</table>'

  cellTemplate: '<div class="story">' +
                  '<a href="{{link}}">' +
                    '<div class="story-image"></div>' +
                  '</a>' +
                  '<span class="story-title">' +
                    '<a href="{{link}}">{{title}}</a>' +
                  '</span>' +
                '</div>'
